<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Mostra detalhes de uma origem da receita...');
    
    $key = \kontas\io\origem::choice();
    
    $climate->info('Registro selecionado:');
    kontas\io\origem::detail($key);
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}