<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Auxilia a conciliação dos saldos...');

    $pularCalculoResultados = $climate->confirm('Deseja pular o cálculo dos resultados?');
    if ($pularCalculoResultados->confirmed() === false) {
        require 'app/calc/resultados.php';
    }

    $periodo = \kontas\util\periodo::parseInput(\kontas\io\periodo::askPeriodo());

    $climate->info('Informe os saldos...');
    $dinheiro = $climate->input('Dinheiro:')->prompt();
    $banrisul = $climate->input('Banrisul:')->prompt();
    $cef = $climate->input('CEF:')->prompt();

    $data = \kontas\ds\periodo::load($periodo);

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
    $areceber = $previsto - $recebido;

    $previsto = 0;
    $gasto = 0;
    $pago = 0;
    foreach ($data['despesas'] as $despesa) {
        foreach ($despesa['previsao'] as $item) {
            $previsto += $item['valor'];
        }
        foreach ($despesa['gasto'] as $item) {
            $gasto += $item['valor'];
            foreach ($item['pagamento'] as $pagamento) {
                $pago += $pagamento['valor'];
            }
        }
    }
    $agastar = $previsto - $gasto;
    $apagar = $gasto - $pago;

    $acumulado = $data['resultados']['acumulado'];

    $disponivel = $dinheiro + $banrisul + $cef + $areceber;

    $dispendios = $agastar + $apagar;

    $resultadoCalculado = $disponivel - $dispendios;

    $diferenca = $resultadoCalculado - $acumulado;

    $climate->info('Conciliação:');
    $climate->padding(KPADDING_LEN)->label('(+) Dinheiro')->result(\kontas\util\number::format($dinheiro));
    $climate->padding(KPADDING_LEN)->label('(+) Banrisul')->result(\kontas\util\number::format($banrisul));
    $climate->padding(KPADDING_LEN)->label('(+) CEF')->result(\kontas\util\number::format($cef));
    $climate->padding(KPADDING_LEN)->label('(+) A Receber')->result(\kontas\util\number::format($areceber));
    $climate->padding(KPADDING_LEN)->label('(=) Disponível')->result(\kontas\util\number::format($disponivel));

    $climate->br();

    $climate->padding(KPADDING_LEN)->label('(-) A Gastar')->result(\kontas\util\number::format($agastar));
    $climate->padding(KPADDING_LEN)->label('(-) A Pagar')->result(\kontas\util\number::format($apagar));
    $climate->padding(KPADDING_LEN)->label('(=) Dispêndios')->result(\kontas\util\number::format($dispendios));

    $climate->br();

    $climate->padding(KPADDING_LEN)->label('(=) Resultado')->result(\kontas\util\number::format($resultadoCalculado));

    $climate->br();

    $climate->padding(KPADDING_LEN)->label('(-) Acumulado')->result(\kontas\util\number::format($acumulado));

    $climate->br();

    $climate->padding(KPADDING_LEN)->label('(=) Diferença')->result(\kontas\util\number::format($diferenca));

    $descricao = sprintf('Ajuste automático por conciliação em %s', date('d/m/Y'));
    $origem = 'Ajuste';
    $aplicacao = 'Ajuste';
    $projeto = 'Ajuste';
    $agrupador = '';
    $parcela = 1;
    $totalParcelas = 1;
    $data = date('Y-m-d');
    $observacao = sprintf('Ajuste automático por conciliação em %s', date('d/m/Y'));
    $pessoa = 'Ajuste';
    $mp = 'Ajuste';
    $vencimento = date('Y-m-d');
    $cc = 'Ajuste';
    
    if ($diferenca < 0) {
        $lancar = $climate->confirm(sprintf(
                        'Existe uma diferença de %s. Deseja lançar uma despesa para ajustar?',
                        number_format($diferenca, 2, ',', '.')
        ));
        if ($lancar->confirmed()) {
            $valor = $diferenca * -1;
            $despesa = \kontas\ds\despesa::addDespesa($periodo, $descricao, $aplicacao, $projeto, $agrupador, $parcela, $totalParcelas, $valor, $data, $observacao);
            
            $gasto = \kontas\ds\despesa::gastar($periodo, $despesa, $pessoa, $mp, $vencimento, $cc, $valor, $data, $observacao);
            
            \kontas\ds\despesa::pagar($periodo, $despesa, $gasto, $valor, $data, $observacao);
            $climate->info('Ajuste realizado.');
        }
    }
    
    if ($diferenca > 0) {
        $lancar = $climate->confirm(sprintf(
                        'Existe uma diferença de %s. Deseja lançar uma despesa para ajustar?',
                        number_format($diferenca, 2, ',', '.')
        ));
        if ($lancar->confirmed()) {
            $valor = $diferenca;
            $receita = \kontas\ds\receita::addReceita($periodo, $descricao, $origem, $pessoa, $cc, $vencimento, $agrupador, $parcela, $totalParcelas, $valor, $data, $observacao);
            
            \kontas\ds\receita::receber($periodo, $receita, $data, $valor, $observacao);
            $climate->info('Ajuste realizado.');
        }
    }
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}