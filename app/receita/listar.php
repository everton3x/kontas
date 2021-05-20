<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Lista as receitas...');

    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());

    $data = kontas\ds\periodo::load($periodo);

    $climate = new \League\CLImate\CLImate();
    $climate->bold()->green()->out(
            'Lista das receitas para ' .
            \kontas\util\periodo::format($data['periodo'])
    );

    foreach ($data['receitas'] as $receita) {
        $previsto = 0;
        $recebido = 0;
        foreach ($receita['previsao'] as $item) {
            $previsto += $item['valor'];
        }
        foreach ($receita['recebimento'] as $item) {
            $recebido += $item['valor'];
        }
        $climate->bold()->green()->out($receita['descricao']);
        $climate->inline('Origem:')->tab(2)->out($receita['origem']);
        $climate->inline('Devedor:')->tab()->out($receita['devedor']);
        $climate->inline('CC:')->tab(2)->out($receita['cc']);
        $climate->inline('Vencimento:')->tab()->out(
                \kontas\util\date::format($receita['vencimento'])
        );
        $climate->inline('Agrupador:')->tab()->out("{$receita['agrupador']} ({$receita['parcela']}/{$receita['totalParcelas']})");
        $climate->padding(KPADDING_LEN)->label('Prevista')->result(
                \kontas\util\number::format($previsto)
        );
        $climate->padding(KPADDING_LEN)->label('Recebida')->result(
                \kontas\util\number::format($recebido)
        );
        $climate->padding(KPADDING_LEN)->label('A Receber')->result(
                \kontas\util\number::format($previsto - $recebido)
        );
        $climate->br();
    }
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}