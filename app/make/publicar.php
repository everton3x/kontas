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

//    print_r($data);exit();
    kontas\util\template::write('index', $data);
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}