<?php

require 'vendor/autoload.php';


try{
    
    $cmd = new \Kontas\Origem\AlteraStatusOrigemCommand();
    $cmd->execute();
    
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}