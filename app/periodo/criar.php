<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Criando novo período...');
    
    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::inPeriodo());

    kontas\ds\periodo::criar($periodo);
    
    $climate->info('Registro salvo:');
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}