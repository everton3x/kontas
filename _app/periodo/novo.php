<?php

require 'vendor/autoload.php';

use Kontas\Periodo\Command\NovoPeriodoCommand;

try {

    $cmd = new NovoPeriodoCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}