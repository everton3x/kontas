<?php

require 'vendor/autoload.php';

use Kontas\Receita\Command\NovaPrevisaoReceitaCommand;

try {

    $cmd = new NovaPrevisaoReceitaCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}