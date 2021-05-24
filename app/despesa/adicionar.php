<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cadastra uma nova despesa...');

    $periodo = \kontas\util\periodo::parseInput(\kontas\io\periodo::askPeriodo());

    $descricao = \kontas\io\generic::askDescricao();

    $aplicacao = \kontas\io\aplicacao::select();

    $projeto = \kontas\io\projeto::select();

    $agrupador = \kontas\io\generic::askAgrupador();

    $valor = \kontas\io\generic::askValor();

    $key = \kontas\ds\despesa::addDespesa($periodo, $descricao, $aplicacao, $projeto, '', 1, 1, $valor, date('Y-m-d'), 'Previsão inicial');

    $gastar = $climate->confirm('Deseja cadastrar como despesa gasta?');
    if ($gastar->confirmed() === true) {
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
    }



    $climate->info("Despesa criada:");
    \kontas\io\despesa::resume($periodo, $key);

    $repetir = $climate->confirm('Deseja repetir a previsão da despesa?');

    while ($repetir->confirmed()) {
        $periodo = \kontas\util\periodo::periodoPosterior($periodo);
        $key = \kontas\ds\despesa::addDespesa($periodo, $descricao, $aplicacao, $projeto, '', 1, 1, $valor, date('Y-m-d'), 'Previsão inicial');
        $climate->info(sprintf(
                        "Despesa criada em %s:",
                        \kontas\util\periodo::format($periodo)
        ));
        \kontas\io\despesa::resume($periodo, $key);
        $repetir = $climate->confirm('Deseja repetir a previsão da despesa?');
    }
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}