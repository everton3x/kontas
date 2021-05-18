<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Altera o status de uma origem da receita...');
    
    $key = \kontas\io\origem::choice();
    
    $climate->info('Registro selecionado:');
    kontas\io\origem::detail($key);
    
    $status = \kontas\io\generic::choiceStatus();
    
    
    kontas\ds\origem::changeStatus($key, $status);
    
    $climate->info('Registro atualizado:');
    kontas\io\origem::detail($key);
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}