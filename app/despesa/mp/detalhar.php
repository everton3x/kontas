<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Mostra detalhes de um meio de pagamento da despesa...');
    
    $key = \kontas\io\mp::choice();
    
    $climate->info('Registro selecionado:');
    kontas\io\mp::detail($key);
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}