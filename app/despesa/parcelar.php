<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cadastra uma nova despesa parcelada...');

    $periodo = \kontas\util\periodo::parseInput(\kontas\io\periodo::askPeriodo('Informe o período inicial [mmaaaa]:'));

    $totalParcelas = \kontas\io\generic::askParcelas();

    $descricao = \kontas\io\generic::askDescricao();

    $aplicacao = \kontas\io\aplicacao::select();

    $projeto = \kontas\io\projeto::select();

    $agrupador = \kontas\io\generic::askAgrupador('Agrupador:');
    if ($agrupador === '') {
        trigger_error('Agrupador é obrigatório.', E_USER_ERROR);
    }

    $valor = \kontas\io\generic::askValor('Valor da parcela [####.##]:');

    $data = \kontas\util\date::parseInput(\kontas\io\generic::askVencimento('Data do gasto [ddmmaaaa]:'));
    $credor = \kontas\io\despesa::askCredor();
    $mp = \kontas\io\mp::select();
    $vencimento = \kontas\util\date::parseInput(\kontas\io\generic::askVencimento());
    $cc = \kontas\io\cc::select();
    $observacao = \kontas\io\generic::askDescricao('Observação do gasto (opcional):');
    
    $parcela = 1;

    while ($parcela <= $totalParcelas) {
        $key = \kontas\ds\despesa::addDespesa($periodo, $descricao, $aplicacao, $projeto, $agrupador, $parcela, $totalParcelas, $valor, date('Y-m-d'), 'Previsão inicial');

        $gasto = \kontas\ds\despesa::gastar($periodo, $key, $credor, $mp, $vencimento, $cc, $valor, $data, $observacao);
        if (\kontas\ds\mp::isAutopagar($mp) === true) {
            \kontas\ds\despesa::pagar($periodo, $key, $gasto, $valor, $data, 'Autopagamento');
        }

        $climate->info(sprintf(
                "Despesa criada em %s:",
                \kontas\util\periodo::format($periodo)
        ));
        \kontas\io\despesa::resume($periodo, $key);

        $periodo = \kontas\util\periodo::periodoPosterior($periodo);
        $vencimento = \kontas\util\date::proximoVencimento($vencimento);
        $parcela++;
    }
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}