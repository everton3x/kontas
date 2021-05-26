<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cria o html auxiliar...');

    $periodos = \kontas\ds\periodo::listAll();
    $index = [];

    $climate->info('Criando índices...');
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
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}