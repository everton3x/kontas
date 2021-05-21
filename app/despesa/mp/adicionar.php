<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Cadastra um novo meio de pagamento da despesa...');
        
    $nome = \kontas\io\generic::askNome();
    $descricao = \kontas\io\generic::askDescricao('Descrição (opcional):');
    $autopagar = \kontas\io\mp::choiceAutopagar();
    
    $key = kontas\ds\mp::add($nome, $descricao, $autopagar);
    
    
    $climate->info('Registro criado:');
    kontas\io\mp::detail($key);
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}