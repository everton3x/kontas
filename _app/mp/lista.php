<?php

require 'vendor/autoload.php';

use Kontas\MP\Command\ListaMPCommand;

try {

    $cmd = new ListaMPCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}