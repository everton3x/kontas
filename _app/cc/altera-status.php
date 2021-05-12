<?php

require 'vendor/autoload.php';

use Kontas\CC\Command\AlteraStatusCCCommand;

try {

    $cmd = new AlteraStatusCCCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}