<?php

require 'vendor/autoload.php';

use Kontas\Origem\Command\DetalheOrigemCommand;

try {

    $cmd = new DetalheOrigemCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}