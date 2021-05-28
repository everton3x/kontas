<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cria o html relativo aos meios de pagamento...');

    $periodos = \kontas\ds\periodo::listAll();
    $mps = [];

    foreach ($periodos as $periodo => $status) {
        $dados = \kontas\ds\periodo::load($periodo);

        foreach ($dados['despesas'] as $despesa) {
            foreach ($despesa['gasto'] as $gasto) {
                if ($gasto['mp'] !== '') {
                    if (!key_exists($gasto['mp'], $mps)) {
                        $mps[$gasto['mp']]['receita'] = 0;
                        $mps[$gasto['mp']]['despesa'] = 0;
                    } else {
                        $mps[$gasto['mp']]['despesa'] += $gasto['valor'];
                    }
                }
            }
        }
    }

    foreach ($mps as $key => $value) {
        $despesa = 0;
        
        if (key_exists('despesa', $mps[$key])) {
            $despesa = $mps[$key]['despesa'];
        }
    }
//    print_r($pessoas);
//    exit();
    kontas\util\template::write('mps', 'mp', ['mps' => $mps]);
//    exit();



    $mps = [];
    foreach ($periodos as $periodo => $status) {
        $dados = \kontas\ds\periodo::load($periodo);
        $nome = '';

        foreach ($dados['despesas'] as $despesa) {
            foreach ($despesa['gasto'] as $item) {
                if ($item['mp'] === '')
                    continue;

                $nome = $item['mp'];
                if (!key_exists($nome, $mps)) {
                    $mps[$nome] = [
                        'periodos' => [],
                        'total' => [
                            'despesa' => 0
                        ]
                    ];
                }
                if (!key_exists($periodo, $mps[$nome]['periodos'])) {
                    $mps[$nome]['periodos'][$periodo] = [
                        'mes' => \kontas\util\periodo::format($periodo),
                        'despesa' => 0,
                    ];
                }

                    $mps[$nome]['periodos'][$periodo]['despesa'] += $item['valor'];
                    $mps[$nome]['total']['despesa'] += $item['valor'];
            }
        }
    }
//    print_r($agrupadores);exit();
    foreach ($mps as $nome => $mp) {
        $filename = "mp-$nome";
//        print_r($agrupador);exit();
        kontas\util\template::write('mp-xxx', $filename, ['nome' => $nome, 'mp' => $mp]);
    }
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}