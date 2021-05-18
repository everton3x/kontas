<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Criando novo período...');
    
    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());

    kontas\ds\periodo::criar($periodo);
    
    $climate->info('Registro criado:');
    
    $filename = kontas\config::PERIODOS_DIR."$periodo".'.json';
    \kontas\io\periodo::resume(
            \kontas\util\json::load($filename)
    );
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}