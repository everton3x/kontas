<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Mostra detalhes de um centro de custos...');
    
    $key = \kontas\io\cc::choice();
    
    $climate->info('Registro selecionado:');
    kontas\io\cc::detail($key);
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}