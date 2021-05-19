<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Cadastra uma nova origem da receita...');
        
    $nome = \kontas\io\generic::askNome();
    $descricao = \kontas\io\generic::askDescricao('Descrição (opcional):');
    
    $key = kontas\ds\origem::add($nome, $descricao);
    
    $climate->info('Registro criado:');
    kontas\io\origem::detail($key);
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}