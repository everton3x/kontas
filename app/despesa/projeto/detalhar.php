<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Mostra detalhes de um projeto da despesa...');
    
    $key = \kontas\io\projeto::choice();
    
    $climate->info('Registro selecionado:');
    kontas\io\projeto::detail($key);
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}