<?php

require 'vendor/autoload.php';

use Kontas\MP\Command\AlteraStatusMPCommand;

try {

    $cmd = new AlteraStatusMPCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}