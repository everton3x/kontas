<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Gasta uma despesa...');

    $periodo = \kontas\util\periodo::parseInput(\kontas\io\periodo::askPeriodo());

    $key = \kontas\io\despesa::choice($periodo);

    $climate->info("Despesa selecionada:");
    \kontas\io\despesa::resume($periodo, $key);

    $credor = \kontas\io\despesa::askCredor();
    $mp = \kontas\io\mp::select();
    $vencimento = \kontas\io\generic::askVencimento();
    $cc = \kontas\io\cc::select();
    $valor = \kontas\io\generic::askValor('Valor do gasto [####.##]:');
    $data = \kontas\util\date::parseInput(\kontas\io\generic::askVencimento('Data do gasto [ddmmaaaa]:'));
    $observacao = \kontas\io\generic::askDescricao('Observação do gasto (opcional):');

    $gasto = \kontas\ds\despesa::gastar($periodo, $key, $credor, $mp, $vencimento, $cc, $valor, $data, $observacao);
    if (\kontas\ds\mp::isAutopagar($mp) === true) {
        \kontas\ds\despesa::pagar($periodo, $key, $gasto, $valor, $data, 'Autopagamento');
    } else {
        $pagar = $climate->confirm('Deseja fazer o pagamento do gasto?');
        if ($pagar->confirmed() === true) {
            \kontas\ds\despesa::pagar($periodo, $key, $gasto, $valor, $data, 'Autopagamento');
        }
    }

    $despesa = (\kontas\ds\despesa::listar($periodo))[$key];
    $previsto = 0;
    foreach ($despesa['previsao'] as $item) {
        $previsto += $item['valor'];
    }
    $gasto = 0;
    foreach ($despesa['gasto'] as $item) {
        $gasto += $item['valor'];
    }
    if ($previsto < $gasto) {
        $diferenca = round($gasto - $previsto, 2);
        $atualizar = $climate->confirm(sprintf(
                        'O valor previsto %s é menor que o valor gasto %s (%s). Deseja suplementar?',
                        \kontas\util\number::format($previsto),
                        \kontas\util\number::format($gasto),
                        \kontas\util\number::format($diferenca),
        ));
        if($atualizar->confirmed()){
            \kontas\ds\despesa::alteraPrevisaoDespesa($periodo, $key, $diferenca, date('Y-m-d'), 'Auto suplementação');
            $climate->info('Previsão suplementada...');
        }
    }

    $climate->info("Despesa gasta:");
    \kontas\io\despesa::resume($periodo, $key);
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}