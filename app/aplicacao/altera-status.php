<?php

require 'vendor/autoload.php';

use Kontas\Origem\Command\AlteraStatusOrigemCommand;

try {

    $cmd = new AlteraStatusOrigemCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}