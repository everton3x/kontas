<?php

require 'vendor/autoload.php';

use Kontas\Origem\Command\ListaOrigemCommand;

try {

    $cmd = new ListaOrigemCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}