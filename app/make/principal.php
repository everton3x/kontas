<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cria o html principal...');

    $pularCalculoResultados = $climate->confirm('Deseja pular o cálculo dos resultados?');
    if ($pularCalculoResultados->confirmed() === false) {
        require 'app/calc/resultados.php';
    }


    //index.html
    $climate->info('Criando index.html');
    $periodos = \kontas\ds\periodo::listAll();
    $data = [];
    foreach ($periodos as $key => $status) {
        $dt = DateTime::createFromFormat('Y-m', $key);
        $ano = $dt->format('Y');
        $data['anos'][$ano] = $ano;
        $mes = $dt->format('M');
        $seq = $dt->format('m');
        $nmes = $dt->format('n');
        $data['periodos'][$ano][$nmes] = [
            'seq' => $seq,
            'nome' => $mes,
            'status' => $status
        ];
    }

    foreach ($data['periodos'] as $ano => $item) {
        for ($i = 1; $i <= 12; $i++) {
            if (key_exists($i, $item) === false) {
                $dt = DateTime::createFromFormat('Y-n', "$ano-$i");
                $data['periodos'][$ano][$i] = [
                    'seq' => $dt->format('m'),
                    'nome' => $dt->format('M'),
                    'status' => -1
                ];
            }
        }
        ksort($data['periodos'][$ano]);
    }
    krsort($data['periodos']);
    ksort($data['anos']);

    kontas\util\template::write('index', 'index', $data);
    //fim index.html
    //aaaa.html
    $climate->info('Criando aaaa.html...');
    $periodos = \kontas\ds\periodo::listAll();
    foreach ($periodos as $key => $status) {
        $dt = DateTime::createFromFormat('Y-m', $key);
        $anos[$dt->format('Y')][$dt->format('m')] = $key;
    }
//    print_r($anos);

    foreach ($anos as $ano => $periodos) {
        $climate->tab()->out("...$ano.html");
        $data = [];
        $data['ano'] = $ano;
        $data['periodos'] = [];

        foreach ($periodos as $key => $periodo) {
            $dados = \kontas\ds\periodo::load($periodo);

            $receitas = 0;
            foreach ($dados['receitas'] as $item) {
                foreach ($item['previsao'] as $subitem) {
                    $receitas += $subitem['valor'];
                }
            }

            $despesas = 0;
            foreach ($dados['receitas'] as $item) {
                foreach ($item['previsao'] as $subitem) {
                    $despesas += $subitem['valor'];
                }
            }

            $resultado = $dados['resultados']['periodo'];
            $acumulado = $dados['resultados']['acumulado'];

            $dt = DateTime::createFromFormat('Y-m', $periodo);
            $data['periodos'][$key] = [
                'nome' => $dt->format('M'),
                'receitas' => $receitas,
                'despesas' => $despesas,
                'resultado' => $resultado,
                'acumulado' => $acumulado
            ];
        }
//        print_r($data);
        kontas\util\template::write('aaaa', $ano, $data);
    }

    //fim aaaa.html
    //aaaa-mm.html
    $climate->info('Criando aaaa-mm.html...');
    $periodos = \kontas\ds\periodo::listAll();
    foreach ($periodos as $key => $status) {
        $data = [];
        $climate->tab()->out("...$key.html");
        $dt = DateTime::createFromFormat('Y-m', $key);
        $data['periodo'] = $key;
        $data['ano'] = $dt->format('Y');
        $data['mes'] = $dt->format('M');
        $data['voltar'] = \kontas\util\periodo::periodoAnterior($key);
        $dt = DateTime::createFromFormat('Y-m', $data['voltar']);
        $data['anterior'] = $dt->format('M/Y');
        $data['avancar'] = \kontas\util\periodo::periodoPosterior($key);
        $dt = DateTime::createFromFormat('Y-m', $data['avancar']);
        $data['posterior'] = $dt->format('M/Y');
        $data['aberto'] = $status;

        $dados = \kontas\ds\periodo::load($key);
        $data['receitas'] = $dados['receitas'];
        $data['despesas'] = $dados['despesas'];
        $data['resultados'] = $dados['resultados'];
        $data['total'] = [
            'receitas' => [
                'previsto' => 0,
                'recebido' => 0,
                'saldo' => 0
            ],
            'despesas' => [
                'previsto' => 0,
                'gasto' => 0,
                'agastar' => 0,
                'pago' => 0,
                'apagar' => 0
            ]
        ];

        foreach ($data['receitas'] as $k => $v) {
            $data['receitas'][$k]['detalhe'] = "{$data['periodo']}-receita-$k";
            $data['receitas'][$k]['total']['previsto'] = 0;
            foreach ($v['previsao'] as $i) {
                $data['receitas'][$k]['total']['previsto'] += $i['valor'];
            }

            $data['receitas'][$k]['total']['recebido'] = 0;
            foreach ($v['recebimento'] as $i) {
                $data['receitas'][$k]['total']['recebido'] += $i['valor'];
            }

            $data['receitas'][$k]['total']['saldo'] = $data['receitas'][$k]['total']['previsto'] - $data['receitas'][$k]['total']['recebido'];

            $data['total']['receitas']['previsto'] += $data['receitas'][$k]['total']['previsto'];
            $data['total']['receitas']['recebido'] += $data['receitas'][$k]['total']['recebido'];
            $data['total']['receitas']['saldo'] += $data['receitas'][$k]['total']['saldo'];
        }

        foreach ($data['despesas'] as $k => $v) {
            $data['despesas'][$k]['detalhe'] = "{$data['periodo']}-despesa-$k";
            $data['despesas'][$k]['total']['previsto'] = 0;
            foreach ($v['previsao'] as $i) {
                $data['despesas'][$k]['total']['previsto'] += $i['valor'];
            }

            $data['despesas'][$k]['total']['gasto'] = 0;
            $data['despesas'][$k]['total']['pago'] = 0;
            foreach ($v['gasto'] as $l => $i) {
                $data['despesas'][$k]['total']['gasto'] += $i['valor'];
                $data['despesas'][$k]['gasto'][$l]['pago'] = 0;
                foreach ($i['pagamento'] as $p){
                    $data['despesas'][$k]['gasto'][$l]['pago'] += $p['valor'];
                    $data['despesas'][$k]['total']['pago'] += $p['valor'];
                }
                $data['despesas'][$k]['gasto'][$l]['apagar'] = $i['valor'] - $data['despesas'][$k]['gasto'][$l]['pago'];
            }

            $data['despesas'][$k]['total']['saldo'] = $data['despesas'][$k]['total']['previsto'] - $data['despesas'][$k]['total']['gasto'];
            $data['despesas'][$k]['total']['apagar'] = $data['despesas'][$k]['total']['gasto'] - $data['despesas'][$k]['total']['pago'];

            $data['total']['despesas']['previsto'] += $data['despesas'][$k]['total']['previsto'];
            $data['total']['despesas']['gasto'] += $data['despesas'][$k]['total']['gasto'];
            $data['total']['despesas']['agastar'] += $data['despesas'][$k]['total']['saldo'];
        }
        
        

//        print_r($data);
        kontas\util\template::write('aaaa-mm', "$key", $data);

        //aaaa-mm-receita-n.html
        //usa so dados $data de aaaa-mm
        $climate->tab()->info('Criando aaaa-mm-receita-n.html...');
        foreach ($data['receitas'] as $seq => $item) {
            $dados = [];
            $dados['periodo'] = $data['periodo'];
            $dados['ano'] = $data['ano'];
            $dados['mes'] = $data['mes'];
            $dados['receita'] = $item;
            $filename = "{$data['periodo']}-receita-$seq";
            $climate->tab(2)->out("...$filename.html");

//            print_r($data);
            kontas\util\template::write('aaaa-mm-receita-n', "$filename", $dados);
        }
        //fim aaaa-mm-receita-n.html
        
        ////aaaa-mm-despesa-n.html
        //usa so dados $data de aaaa-mm
        $climate->tab()->info('Criando aaaa-mm-despesa-n.html...');
        foreach ($data['despesas'] as $seq => $item) {
            $dados = [];
            $dados['periodo'] = $data['periodo'];
            $dados['ano'] = $data['ano'];
            $dados['mes'] = $data['mes'];
            $dados['despesa'] = $item;
            $filename = "{$data['periodo']}-despesa-$seq";
            $climate->tab(2)->out("...$filename.html");

//            print_r($data);
            kontas\util\template::write('aaaa-mm-despesa-n', "$filename", $dados);
        }
        //fim aaaa-mm-despesa-n.html
    }
    //fim aaaa-mm.html
    
    //mais.html
    $climate->tab()->info('Criando mais.html...');
    kontas\util\template::write('mais', 'mais', []);
    //fim mais.html
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}