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
        
        foreach ($periodos as $key => $periodo){
            $dados = \kontas\ds\periodo::load($periodo);
            
            $receitas = 0;
            foreach ($dados['receitas'] as $item){
                foreach ($item['previsao'] as $subitem){
                    $receitas += $subitem['valor'];
                }
            }
            
            $despesas = 0;
            foreach ($dados['receitas'] as $item){
                foreach ($item['previsao'] as $subitem){
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
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}