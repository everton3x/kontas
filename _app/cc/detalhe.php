<?php

require 'vendor/autoload.php';

use Kontas\CC\Command\DetalheCCCommand;

try {

    $cmd = new DetalheCCCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}