<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cria o html relativo aos agrupadores...');

    $periodos = \kontas\ds\periodo::listAll();
    $agrupadores = [];

    foreach ($periodos as $periodo => $status) {
        $dados = \kontas\ds\periodo::load($periodo);

        foreach ($dados['receitas'] as $receita) {
            if ($receita['agrupador'] !== '') {
                if (!key_exists($receita['agrupador'], $agrupadores)) {
                    $agrupadores[$receita['agrupador']]['receita'] = 0;
                    $agrupadores[$receita['agrupador']]['despesa'] = 0;
                }
                foreach ($receita['previsao'] as $item) {
                    $agrupadores[$receita['agrupador']]['receita'] += $item['valor'];
                }
            }
        }

        foreach ($dados['despesas'] as $despesa) {
            if ($despesa['agrupador'] !== '') {
                if (!key_exists($despesa['agrupador'], $agrupadores)) {
                    $agrupadores[$despesa['agrupador']]['receita'] = 0;
                    $agrupadores[$despesa['agrupador']]['despesa'] = 0;
                }
                foreach ($despesa['previsao'] as $item) {
                    $agrupadores[$despesa['agrupador']]['despesa'] += $item['valor'];
                }
            }
        }
    }

    foreach ($agrupadores as $key => $value) {
        $receita = 0;
        $despesa = 0;
        if (key_exists('receita', $agrupadores[$key])) {
            $receita = $agrupadores[$key]['receita'];
        }
        if (key_exists('despesa', $agrupadores[$key])) {
            $despesa = $agrupadores[$key]['despesa'];
        }
        $agrupadores[$key]['resultado'] = $receita - $despesa;
    }
//    print_r($agrupadores);
//    exit();
    kontas\util\template::write('agrupadores', 'agrupadores', ['agrupadores' => $agrupadores]);
    
    
    
    
    $agrupadores = [];
    foreach ($periodos as $periodo => $status) {
        $dados = \kontas\ds\periodo::load($periodo);
        $nome = '';
        
        foreach ($dados['receitas'] as $item){
            if($item['agrupador'] === '') continue;
            
            $nome = $item['agrupador'];
            if(!key_exists($nome, $agrupadores)){
                $agrupadores[$nome] = [
                    'periodos' => [],
                    'total' => [
                        'receita' => 0,
                        'despesa' => 0
                    ]
                ];
            }
            if(!key_exists($periodo, $agrupadores[$nome]['periodos'])){
                $agrupadores[$nome]['periodos'][$periodo] = [
                    'mes' => \kontas\util\periodo::format($periodo),
                    'receita' => 0,
                    'despesa' => 0
                ];
            }
            
            foreach ($item['previsao'] as $previsao){
                $agrupadores[$nome]['periodos'][$periodo]['receita'] += $previsao['valor'];
                $agrupadores[$nome]['total']['receita'] += $previsao['valor'];
            }
        }
        
        foreach ($dados['despesas'] as $item){
            if($item['agrupador'] === '') continue;
            
            $nome = $item['agrupador'];
            if(!key_exists($nome, $agrupadores)){
                $agrupadores[$nome] = [
                    'periodos' => [],
                    'total' => [
                        'receita' => 0,
                        'despesa' => 0
                    ]
                ];
            }
            if(!key_exists($periodo, $agrupadores[$nome]['periodos'])){
                $agrupadores[$nome]['periodos'][$periodo] = [
                    'mes' => \kontas\util\periodo::format($periodo),
                    'receita' => 0,
                    'despesa' => 0,
                ];
            }
            
            foreach ($item['previsao'] as $previsao){
                $agrupadores[$nome]['periodos'][$periodo]['despesa'] += $previsao['valor'];
                $agrupadores[$nome]['total']['despesa'] += $previsao['valor'];
            }
        }
    }
//    print_r($agrupadores);exit();
    foreach ($agrupadores as $nome => $agrupador){
        $filename = "agrupador-$nome";
//        print_r($agrupador);exit();
        kontas\util\template::write('agrupador-xxx', $filename, ['nome' => $nome, 'agrupador' => $agrupador]);
    }
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}