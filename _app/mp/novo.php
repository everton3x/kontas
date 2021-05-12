<?php

require 'vendor/autoload.php';

use Kontas\MP\Command\NovoMPCommand;

try {

    $cmd = new NovoMPCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}