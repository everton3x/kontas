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
    
    
    $gastar = $climate->confirm('Deseja cadastrar como despesa gasta?');
    if($gastar->confirmed() === true){
        $credor = \kontas\io\despesa::askCredor();
        $mp = \kontas\io\mp::select();
        $vencimento = \kontas\io\generic::askVencimento();
        $cc = \kontas\io\cc::select();
        $valor = \kontas\io\generic::askValor('Valor do gasto [####.##]:');
        $data = \kontas\io\generic::askVencimento('Data do gasto [ddmmaaaa]:');
        $observacao = \kontas\io\generic::askDescricao('Observação do gasto (opcional):');
        
        \kontas\ds\despesa::addGasto($periodo, $key, $credor, $mp, $vencimento, $cc, $valor, $data, $observacao);
    }
    
    
    $climate->info("Despesa criada:");
    \kontas\io\despesa::resume($periodo, $key);
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}