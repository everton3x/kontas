<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Cadastra uma nova despesa...');

    $periodo = \kontas\util\periodo::parseInput(\kontas\io\periodo::askPeriodo());
    
    $descricao = \kontas\io\generic::askDescricao();
    
    $aplicacao = \kontas\io\aplicacao::select();
    
    $projeto = \kontas\io\projeto::select();
    
    $agrupador = \kontas\io\generic::askAgrupador();
    
    $valor = \kontas\io\generic::askValor();
    
    $key = \kontas\ds\despesa::addDespesa($periodo, $descricao, $aplicacao, $projeto, '', 1, 1, $valor, date('Y-m-d'), 'Previsão inicial');
    
    $climate->info("Despesa criada:");
    
    \kontas\io\despesa::resume($periodo, $key);
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}