<?php

function writeLog($message, $pretty = true, ...$vars)
{
    $json = json_encode($vars, JSON_UNESCAPED_UNICODE | ($pretty ? JSON_PRETTY_PRINT : 0));

    $logDirectory = __DIR__ . '1cbit/logs';
    $logFile = $logDirectory . '/log.log';

    if (!is_dir($logDirectory)) {
        mkdir($logDirectory, 0755, true);
    }

    if (file_exists($logFile) && filesize($logFile) > 5 * 1024 * 1024) {
        unlink($logFile);
    }

    $entry = date("d.m.Y H:i:s") . " - {$message} - " . $json;

    file_put_contents($logFile, $entry . PHP_EOL . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function dump(...$vars): void
{
    echo '<style> 
            .dump { 
                background-color: #000000; color: #ffee00ff; 
                border: 1px solid #000000; padding: 10px; 
                margin: 10px; border-radius: 5px; 
                margin-bottom: 50px; 
            }
            </style>';
    foreach ($vars as $var) {
        echo '<div class="dump"><pre>';
        var_export($var);
        echo '</pre></div>';
    }
}

function sendBitrix($url, $dataArr) {
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_POSTFIELDS => $dataArr,
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    if (isset(json_decode($result, true)['error'])) {
        file_put_contents('errors.log', "ERROR || " . date("d-m-Y H:i:s") . " || Ошибка отправки в битрикс || " . implode(' ', json_decode($result, true)) . " || " . $url . "\n", FILE_APPEND);
    }
    return $result;
}

function sendTelegram($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    if (isset(json_decode($result, true)['error'])) {
        file_put_contents('errors.log', "ERROR || " . date("d-m-Y H:i:s") . " || Ошибка дублирования сделки в телеграм || " . json_decode($result, true) . " || " . $url . "\n", FILE_APPEND);
    }
        
    return $result;
}

?>