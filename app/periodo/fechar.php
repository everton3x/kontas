<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Fecha um período...');
    
    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());

    \kontas\ds\periodo::close($periodo);
    
    $climate->info('Registro fechado:');
    
    $filename = kontas\config::PERIODOS_DIR."$periodo".'.json';
    \kontas\io\periodo::resume(
            \kontas\util\json::load($filename)
    );
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}