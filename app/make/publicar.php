<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cria o html para publicar no Github Pages...');

    $calcular = $climate->confirm('É aconselhável recalcular tudo antes. Deseja recalcular?');
    if ($calcular->confirmed()) {
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
    $climate->info('Criando aaaa-mm.html...');
    $periodos = \kontas\ds\periodo::listAll();
    foreach ($periodos as $key => $status) {
        $data = [];
        $climate->tab()->out("...$key.html");
        $dt = DateTime::createFromFormat('Y-m', $key);
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
        
        foreach ($data['receitas'] as $k => $v){
            $data['receitas'][$k]['total']['previsto'] = 0;
            foreach ($v['previsao'] as $i){
                $data['receitas'][$k]['total']['previsto'] += $i['valor'];
            }
            
            $data['receitas'][$k]['total']['recebido'] = 0;
            foreach ($v['recebimento'] as $i){
                $data['receitas'][$k]['total']['recebido'] += $i['valor'];
            }
            
            $data['receitas'][$k]['total']['saldo'] = $data['receitas'][$k]['total']['previsto'] - $data['receitas'][$k]['total']['recebido'];
            
            $data['total']['receitas']['previsto'] += $data['receitas'][$k]['total']['previsto'];
            $data['total']['receitas']['recebido'] += $data['receitas'][$k]['total']['recebido'];
            $data['total']['receitas']['saldo'] += $data['receitas'][$k]['total']['saldo'];
        }

//        print_r($data);
        kontas\util\template::write('aaaa-mm', "$key", $data);
    }
    //aaaa-mm.html
    //fim aaaa-mm.html
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}