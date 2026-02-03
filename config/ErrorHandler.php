<?php

namespace config;

require_once('n8ZSdaZE43Glu0ZkMeNVlOXwvOFRm5tc.php');

class ErrorHandler
{
    public function register()
    {
        set_error_handler([$this, 'errorHandler']);
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }

    function errorHandler($errno, $errstr)
    {
        $this->showError($errno, $errstr);
        return true;
    }

    function fatalErrorHandler()
    {
        if (!empty($error = error_get_last()) && $error['type'] && (E_ERROR || E_PARSE || E_COMPILE_ERROR || E_CORE_ERROR)) {
            ob_get_clean();
            $this->showError($error['type'], $error['message']);
        }
    }

    function showError($errno, $errstr)
    {
        global $urlTg;
        file_put_contents('errors.log', "ERROR || " . date("d-m-Y H:i:s") . " || Системная ошибка: " . $errno . " || " . $errstr . '\n', FILE_APPEND);
    }
}