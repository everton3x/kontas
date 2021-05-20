<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Detalha uma receita ...');

    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());

    $key = \kontas\io\receita::choice($periodo);
    
    $data = \kontas\ds\periodo::load($periodo);
    
    \kontas\io\receita::details($data['receitas'][$key]);
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}