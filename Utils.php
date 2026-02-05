<?php

class Utils
{
    private const LOG_TYPE = [
        'INFO' => 'incomingRequests.log',
        'NOTE' => 'contactData.log',
        'ERROR' => 'error.log',
    ];

    /**
     * Записывает лог в соответствующий файл
     *
     * @param string $message   Указывается тип лога (INFO, NOTE, ERROR, или произвольная строка)
     * @param bool   $pretty Форматировать JSON красиво
     * @param mixed  ...$vars Данные для логирования
     *
     * @return void
     */
    public static function write($message, $pretty = true, ...$vars): void
    {
        $json = json_encode($vars, JSON_UNESCAPED_UNICODE | ($pretty ? JSON_PRETTY_PRINT : 0));

        $logDirectory = __DIR__ . '/logs/';
        $logFile = $logDirectory . (self::LOG_TYPE[$message] ?? 'test.log');

        if (!is_dir($logDirectory)) {
            mkdir($logDirectory, 0755, true);
        }

        if (file_exists($logFile) && filesize($logFile) > 5 * 1024 * 1024) {
            unlink($logFile);
        }

        $entry = date("d.m.Y H:i:s") . " - {$message} - " . $json;

        file_put_contents($logFile, $entry . PHP_EOL, FILE_APPEND | LOCK_EX);
    }


    public static function error(...$vars): void
    {
        self::write("ERROR", false, ...$vars);
        if (Config::IS_DEV) {
            self::dump($vars);
        }
    }

    public static function info(...$vars): void
    {
        self::write("INFO", false, ...$vars);
    }

    public static function note(...$vars): void
    {
        self::write("NOTE", false, ...$vars);
    }

    public static function test($message, ...$vars): void
    {
        self::write($message, false, ...$vars);
    }


    public static function pageDebug($die = true, ...$vars)
    {
        $debugType = $die ? "dd" : "dump";
        $color = $die ? "#23ff00" : "#ffee00ff";
        echo "<style> 
            .{$debugType} { 
                background-color: #000000; color:{$color}; 
                border: 1px solid #000000; padding: 10px; 
                margin: 10px; border-radius: 5px; 
                margin-bottom: 50px; 
            }
            </style>";
        foreach ($vars as $var) {
            echo "<div class=\"{$debugType}\"><pre>";
            var_export($var);
            echo "</pre></div>";
        }
        die();
    }

    public static function dd(...$vars)
    {
        self::pageDebug(true, ...$vars);
    }

    public static function dump(...$vars)
    {
        self::pageDebug(false, ...$vars);
    }

    /**
     * Нормализует номер телефона в маску +7 XXX XXX-XX-XX
     *
     * @param string $phone Исходный номер телефона
     * @return string|null Нормализованный номер или null при ошибке
     */
    public static function normalizePhone(string $phone): ?string
    {
        $clean = preg_replace('/[^0-9+]/', '', $phone);

        if (str_starts_with($clean, '8') && strlen($clean) === 11) {
            $clean = '+7' . substr($clean, 1);
        } elseif (str_starts_with($clean, '7') && strlen($clean) === 11) {
            $clean = '+' . $clean;
        } elseif (ctype_digit($clean) && strlen($clean) === 10) {
            $clean = '+7' . $clean;
        } elseif ($clean[0] !== "+" && strlen($clean) > 11) {
            return null;
        }

        if (preg_match('/^\+7(\d{10})$/', $clean, $m)) {
            $d = $m[1];
            return "+7 {$d[0]}{$d[1]}{$d[2]} {$d[3]}{$d[4]}{$d[5]}-{$d[6]}{$d[7]}-{$d[8]}{$d[9]}";
        }

        return $clean;
    }

    public static function extractRequestData(): array
    {
        return array_merge(array_change_key_case($_POST ?? null), array_change_key_case($_GET ?? null));
    }

    public static function normalizeUrl($url)
    {
        return str_contains($url, ".ru/") ? strtolower(strtok($url, '/')) : $url;
    }

    public static function extractRequestUtm(array $request): ?array
    {
        $utms = [];
        foreach ($request as $key => $value) {
            if (str_contains($key, "utm")) {
                $utms[strtoupper($key)] = $value;
            }
        }
        return $utms ?: null;
    }

    public static function getCityId(array $request): ?int
    {
        if ($request["city"] == 72) $cityId = 51;
        else if ($request["city"] == 66) $cityId = 53;
        return $cityId ?? null;
    }

}
