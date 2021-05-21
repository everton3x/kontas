<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Altera o status de uma aplicação da despesa...');
    
    $key = \kontas\io\aplicacao::choice();
    
    $climate->info('Registro selecionado:');
    kontas\io\aplicacao::detail($key);
    
    $status = \kontas\io\generic::choiceStatus();
    
    
    kontas\ds\aplicacao::changeStatus($key, $status);
    
    $climate->info('Registro atualizado:');
    kontas\io\aplicacao::detail($key);
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}