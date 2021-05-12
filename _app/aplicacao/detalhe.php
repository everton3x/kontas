<?php

require 'vendor/autoload.php';

use Kontas\Aplicacao\Command\DetalheAplicacaoCommand;

try {

    $cmd = new DetalheAplicacaoCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}