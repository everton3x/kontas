<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cria o html relativo aos devedores/credores...');

    $periodos = \kontas\ds\periodo::listAll();
    $pessoas = [];

    foreach ($periodos as $periodo => $status) {
        $dados = \kontas\ds\periodo::load($periodo);

        foreach ($dados['receitas'] as $receita) {
            if ($receita['devedor'] !== '') {
                if (!key_exists($receita['devedor'], $pessoas)) {
                    $pessoas[$receita['devedor']]['receita'] = 0;
                    $pessoas[$receita['devedor']]['despesa'] = 0;
                }
                foreach ($receita['previsao'] as $item) {
                    $pessoas[$receita['devedor']]['receita'] += $item['valor'];
                }
            }
        }

        foreach ($dados['despesas'] as $despesa) {
            foreach ($despesa['gasto'] as $gasto) {
                if ($gasto['credor'] !== '') {
                    if (!key_exists($gasto['credor'], $pessoas)) {
                        $pessoas[$gasto['credor']]['receita'] = 0;
                        $pessoas[$gasto['credor']]['despesa'] = 0;
                    } else {
                        $pessoas[$gasto['credor']]['despesa'] += $gasto['valor'];
                    }
                }
            }
        }
    }

    foreach ($pessoas as $key => $value) {
        $receita = 0;
        $despesa = 0;
        if (key_exists('receita', $pessoas[$key])) {
            $receita = $pessoas[$key]['receita'];
        }
        if (key_exists('despesa', $pessoas[$key])) {
            $despesa = $pessoas[$key]['despesa'];
        }
        $pessoas[$key]['resultado'] = $receita - $despesa;
    }
//    print_r($pessoas);
//    exit();
    kontas\util\template::write('pessoas', 'pessoas', ['pessoas' => $pessoas]);
//    exit();



    $pessoas = [];
    foreach ($periodos as $periodo => $status) {
        $dados = \kontas\ds\periodo::load($periodo);
        $nome = '';

        foreach ($dados['receitas'] as $item) {
            if ($item['devedor'] === '')
                continue;

            $nome = $item['devedor'];
            if (!key_exists($nome, $pessoas)) {
                $pessoas[$nome] = [
                    'periodos' => [],
                    'total' => [
                        'receita' => 0,
                        'despesa' => 0
                    ]
                ];
            }
            if (!key_exists($periodo, $pessoas[$nome]['periodos'])) {
                $pessoas[$nome]['periodos'][$periodo] = [
                    'mes' => \kontas\util\periodo::format($periodo),
                    'receita' => 0,
                    'despesa' => 0
                ];
            }

            foreach ($item['previsao'] as $previsao) {
                $pessoas[$nome]['periodos'][$periodo]['receita'] += $previsao['valor'];
                $pessoas[$nome]['total']['receita'] += $previsao['valor'];
            }
        }

        foreach ($dados['despesas'] as $despesa) {
            foreach ($despesa['gasto'] as $item) {
                if ($item['credor'] === '')
                    continue;

                $nome = $item['credor'];
                if (!key_exists($nome, $pessoas)) {
                    $pessoas[$nome] = [
                        'periodos' => [],
                        'total' => [
                            'receita' => 0,
                            'despesa' => 0
                        ]
                    ];
                }
                if (!key_exists($periodo, $pessoas[$nome]['periodos'])) {
                    $pessoas[$nome]['periodos'][$periodo] = [
                        'mes' => \kontas\util\periodo::format($periodo),
                        'receita' => 0,
                        'despesa' => 0,
                    ];
                }

                    $pessoas[$nome]['periodos'][$periodo]['despesa'] += $item['valor'];
                    $pessoas[$nome]['total']['despesa'] += $item['valor'];
            }
        }
    }
//    print_r($agrupadores);exit();
    foreach ($pessoas as $nome => $pessoa) {
        $filename = "pessoa-$nome";
//        print_r($agrupador);exit();
        kontas\util\template::write('pessoa-xxx', $filename, ['nome' => $nome, 'pessoa' => $pessoa]);
    }
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}