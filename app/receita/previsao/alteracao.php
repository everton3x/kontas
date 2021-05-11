<?php

require 'vendor/autoload.php';

use Kontas\Receita\Command\AlteraPrevisaoReceitaCommand;

try {

    $cmd = new AlteraPrevisaoReceitaCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}