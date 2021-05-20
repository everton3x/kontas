<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Mostra um resumo das receitas...');

    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());

    $data = kontas\ds\periodo::load($periodo);

    $previsto = 0;
    $recebido = 0;
    foreach ($data['receitas'] as $receita) {
        foreach ($receita['previsao'] as $item) {
            $previsto += $item['valor'];
        }
        foreach ($receita['recebimento'] as $item) {
            $recebido += $item['valor'];
        }
    }


    $climate = new \League\CLImate\CLImate();
    $climate->bold()->green()->out(
            'Resumo das receitas para '.
            \kontas\util\periodo::format($data['periodo'])
    );
    $climate->padding(KPADDING_LEN)->label('Prevista')->result(
            \kontas\util\number::format($previsto)
    );
    $climate->padding(KPADDING_LEN)->label('Recebida')->result(
            \kontas\util\number::format($recebido)
    );
    $climate->padding(KPADDING_LEN)->label('A Receber')->result(
            \kontas\util\number::format($previsto - $recebido)
    );
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}