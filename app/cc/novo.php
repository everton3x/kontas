<?php

require 'vendor/autoload.php';

use Kontas\Origem\Command\NovaOrigemCommand;

try {

    $cmd = new NovaOrigemCommand();
    $cmd->execute();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
    echo $ex->getTraceAsString(), PHP_EOL;
}