<?php

require 'vendor/autoload.php';

use Kontas\Aplicacao\Command\NovaAplicacaoCommand;

try {

    $cmd = new NovaAplicacaoCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}