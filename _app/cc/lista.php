<?php

require 'vendor/autoload.php';

use Kontas\CC\Command\ListaCCCommand;

try {

    $cmd = new ListaCCCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}