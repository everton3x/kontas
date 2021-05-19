<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Cadastra um novo centro de custos...');
        
    $nome = \kontas\io\generic::askNome();
    $descricao = \kontas\io\generic::askDescricao('Descrição (opcional):');
    
    $key = kontas\ds\cc::add($nome, $descricao);
    
    $climate->info('Registro criado:');
    kontas\io\cc::detail($key);
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}