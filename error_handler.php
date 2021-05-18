<?php

function error_handler($errno, $errstr, $errfile, $errline){
    $climate = new \League\CLImate\CLImate();
    
    $msg = "$errstr".PHP_EOL."$errfile:$errline";
    switch ($errno){
        case E_USER_ERROR:
            $climate->error($msg);
            exit($errno);
            break;
        case E_USER_WARNING:
            $climate->lightRed()->out($msg);
            break;
        case E_USER_NOTICE:
            $climate->yellow()->out($msg);
            break;
        default :
            $climate->bold()->out($msg);
            break;
    }
    
    return true;
    
}

set_error_handler('error_handler');