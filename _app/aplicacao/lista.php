<?php

require 'vendor/autoload.php';

use Kontas\Aplicacao\Command\ListaAplicacaoCommand;

try {

    $cmd = new ListaAplicacaoCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}