<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cria o html auxiliar...');

    $periodos = \kontas\ds\periodo::listAll();

    $climate->info('Criando índices...');
    $index = [];
    foreach ($periodos as $periodo => $status) {
        $dados = \kontas\ds\periodo::load($periodo);
        $dt = DateTime::createFromFormat('Y-m', $periodo);
        $ano = $dt->format('Y');
        $mes = $dt->format('M');
        
        foreach ($dados['receitas'] as $item) {
            $index['origem'][$item['origem']]['periodo'][$ano][$mes] = [
                'periodo' => $periodo
            ];
            $index['origem'][$item['origem']]['periodo'][$ano][$mes]['receitas'] = [
                'previsto' => 0,
                'recebido' => 0,
                'areceber' => 0
            ];
            foreach ($item['previsao'] as $subitem){
                $index['origem'][$item['origem']]['periodo'][$ano][$mes]['receitas']['previsto'] += $subitem['valor'];
            }
            foreach ($item['recebimento'] as $subitem){
                $index['origem'][$item['origem']]['periodo'][$ano][$mes]['receitas']['recebido'] += $subitem['valor'];
            }
            
            $index['origem'][$item['origem']]['periodo'][$ano][$mes]['receitas']['areceber'] = $index['origem'][$item['origem']]['periodo'][$ano][$mes]['receitas']['previsto'] - $index['origem'][$item['origem']]['periodo'][$ano][$mes]['receitas']['recebido'];
        }
        
        foreach ($dados['despesas'] as $item) {
            $index['aplicacao'][$item['aplicacao']]['periodo'][$ano][$mes] = [
                'periodo' => $periodo
            ];
            $index['aplicacao'][$item['aplicacao']]['periodo'][$ano][$mes]['despesas'] = [
                'previsto' => 0,
                'gasto' => 0,
                'agastar' => 0
            ];
            foreach ($item['previsao'] as $subitem){
                $index['aplicacao'][$item['aplicacao']]['periodo'][$ano][$mes]['despesas']['previsto'] += $subitem['valor'];
            }
            foreach ($item['gasto'] as $subitem){
                $index['aplicacao'][$item['aplicacao']]['periodo'][$ano][$mes]['despesas']['gasto'] += $subitem['valor'];
            }
            
            $index['aplicacao'][$item['aplicacao']]['periodo'][$ano][$mes]['despesas']['agastar'] = $index['aplicacao'][$item['aplicacao']]['periodo'][$ano][$mes]['despesas']['previsto'] - $index['aplicacao'][$item['aplicacao']]['periodo'][$ano][$mes]['despesas']['gasto'];
        }
    }

    //origem
    $climate->info('Criando origens.html');
    $data = [];
    $data['items'] = \kontas\ds\origem::listAll();
    $data['total'] = sizeof($data['items']);
    kontas\util\template::write('origens', 'origens', $data);
    
    foreach ($index['origem'] as $nome => $dados){
        $filename = "origem-$nome";
        $data = [
            'nome' => $nome,
            'periodo' => $dados['periodo']
        ];
        kontas\util\template::write('origem-xxx', $filename, $data);
    }
    //fim origem
    

    //aplicacao
    $climate->info('Criando aplicacoes.html');
    $data = [];
    $data['items'] = \kontas\ds\aplicacao::listAll();
    $data['total'] = sizeof($data['items']);
    kontas\util\template::write('aplicacoes', 'aplicacoes', $data);
    
    foreach ($index['aplicacao'] as $nome => $dados){
        $filename = "aplicacao-$nome";
        $data = [
            'nome' => $nome,
            'periodo' => $dados['periodo']
        ];
        kontas\util\template::write('aplicacao-xxx', $filename, $data);
    }
    //fim aplicacao
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}