<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Altera o status de um centro de custos...');
    
    $key = \kontas\io\cc::choice();
    
    $climate->info('Registro selecionado:');
    kontas\io\cc::detail($key);
    
    $status = \kontas\io\generic::choiceStatus();
    
    
    kontas\ds\cc::changeStatus($key, $status);
    
    $climate->info('Registro atualizado:');
    kontas\io\cc::detail($key);
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}