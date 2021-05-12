<?php

require 'vendor/autoload.php';

use Kontas\Aplicacao\Command\AlteraStatusAplicacaoCommand;

try {

    $cmd = new AlteraStatusAplicacaoCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}