<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Lista os períodos existentes...');
    
    
    
    \kontas\io\periodo::list(
            \kontas\io\periodo::choiceStatus()
    );
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}