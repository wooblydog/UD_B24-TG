<?php
require_once "helpers.php";

class Utils
{
    public static function write($message, $pretty = true, ...$vars): void
    {
        $json = json_encode($vars, JSON_UNESCAPED_UNICODE | ($pretty ? JSON_PRETTY_PRINT : 0));

        $logDirectory = __DIR__ . '/logs';
        $logFile = $message == "INFO" ? $logDirectory . '/log.log' : $logDirectory . '/error.log';

        if (!is_dir($logDirectory)) {
            mkdir($logDirectory, 0755, true);
        }

        if (file_exists($logFile) && filesize($logFile) > 5 * 1024 * 1024) {
            unlink($logFile);
        }

        $entry = date("d.m.Y H:i:s") . " - {$message} - " . $json;

        file_put_contents($logFile, $entry . PHP_EOL . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public static function error(...$vars): void
    {
        self::write("ERROR", false, ...$vars);
    }

    public static function info(...$vars): void
    {
        self::write("INFO", false, ...$vars);
    }

    public static function debug(...$vars): void
    {
        self::write("DEBUG", false, ...$vars);
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
        self::pageDebug(true, $vars);
    }

    public static function dump(...$vars)
    {
        self::pageDebug(false, $vars);
    }

}
