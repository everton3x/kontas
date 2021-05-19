<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cadastra uma nova previsão da receita parcelada...');

    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());

    $descricao = \kontas\io\generic::askDescricao();

    $origem = \kontas\io\origem::select();

    $devedor = \kontas\io\generic::askDevedor();

    $cc = \kontas\io\cc::select();

    $vencimento = \kontas\io\generic::askVencimento('Primeiro vencimento [ddmmaaaa] (opcional):');
    if ($vencimento === '') {
        $vencimento = date('Y-m-d');
    } else {
        $vencimento = \kontas\util\date::parseInput($vencimento);
    }

    $agrupador = \kontas\io\generic::askAgrupador('Agrupador:');
    if($agrupador === ''){
        trigger_error('Um agrupador é exigido.', E_USER_ERROR);
    }
    
    $totalParcelas = \kontas\io\generic::askParcelas();

    $valor = \kontas\io\generic::askValor('Valor da primeira parcela [####.##]:');
    
    $parcela = 1;
    $climate->info(sprintf(
                "Salvando parcela %s com valor %s no período %s",
                $parcela,
                \kontas\util\number::format($valor),
                \kontas\util\periodo::format($periodo)
        ));
        $key = \kontas\ds\receita::addReceita($periodo, $descricao, $origem, $devedor, $cc, $vencimento, $agrupador, $parcela, $totalParcelas, $valor, date('Y-m-d'), 'Previsão inicial');
        
    do{
        $parcela++;
        $periodo = \kontas\util\periodo::periodoPosterior($periodo);
        $climate->out("Parcela $parcela: Valor [####.##] ou ENTER para receber $valor:");
        $input = $climate->input('>');
        $confirmarValor = $input->prompt();
        if($confirmarValor !== ''){
            $valor = $confirmarValor;
        }
        
        $proximoVencimento = \kontas\util\date::proximoVencimento($vencimento);

        $vencimento = \kontas\io\generic::askVencimento("Parcela $parcela: Vencimento [ddmmaaaa] ou ENTER para $proximoVencimento:");
        if ($vencimento === '') {
            $vencimento = $proximoVencimento;
        } else {
            $vencimento = \kontas\util\date::parseInput($vencimento);
        }
        
        $climate->info(sprintf(
                "Salvando parcela %s com valor %s no período %s",
                $parcela,
                \kontas\util\number::format($valor),
                \kontas\util\periodo::format($periodo)
        ));
        $key = \kontas\ds\receita::addReceita($periodo, $descricao, $origem, $devedor, $cc, $vencimento, $agrupador, $parcela, $totalParcelas, $valor, date('Y-m-d'), 'Previsão inicial');
    }while($parcela < $totalParcelas);

    $climate->info('Parcelamento criado.');
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}