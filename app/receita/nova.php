<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Cadastra uma nova previsão da receita...');
    
    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());
    
    $descricao = \kontas\io\generic::askDescricao();
    
    $climate->info('Registro criado:');
    
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}