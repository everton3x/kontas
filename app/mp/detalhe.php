<?php

require 'vendor/autoload.php';

use Kontas\MP\Command\DetalheMPCommand;

try {

    $cmd = new DetalheMPCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}