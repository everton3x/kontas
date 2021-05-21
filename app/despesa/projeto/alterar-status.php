<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Altera o status de um projeto da despesa...');
    
    $key = \kontas\io\projeto::choice();
    
    $climate->info('Registro selecionado:');
    kontas\io\projeto::detail($key);
    
    $status = \kontas\io\generic::choiceStatus();
    
    
    kontas\ds\projeto::changeStatus($key, $status);
    
    $climate->info('Registro atualizado:');
    kontas\io\projeto::detail($key);
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}