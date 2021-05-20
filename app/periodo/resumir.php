<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Mostra um resumo do período...');
    
    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());

    $filename = kontas\config::PERIODOS_DIR."$periodo".'.json';
    \kontas\io\periodo::resume(
            \kontas\util\json::load($filename)
    );
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}