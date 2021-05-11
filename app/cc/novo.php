<?php

require 'vendor/autoload.php';

use Kontas\CC\Command\NovoCCCommand;

try {

    $cmd = new NovoCCCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}