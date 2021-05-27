<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Paga várias despesas de um meio de pagamento...');

    $periodo = \kontas\util\periodo::parseInput(\kontas\io\periodo::askPeriodo());

    $mp = \kontas\io\mp::select();

    $dados = \kontas\ds\periodo::load($periodo);

    $gasto = 0;
    $pago = 0;
    foreach ($dados['despesas'] as $despesa) {
        foreach ($despesa['gasto'] as $item) {
            if ($item['mp'] === $mp) {
                $gasto += $item['valor'];
                foreach ($item['pagamento'] as $pagamento) {
                    $pago += $pagamento['valor'];
                }
            }
        }
    }

    $climate->info(sprintf(
                    'Resumo de %s em %s',
                    $mp,
                    kontas\util\periodo::format($periodo)
    ));
    $climate->padding(KPADDING_LEN)->label('(+) Valor gasto')->result(
            \kontas\util\number::format($gasto)
    );
    $climate->padding(KPADDING_LEN)->label('(-) Valor pago')->result(
            \kontas\util\number::format($pago)
    );
    $climate->padding(KPADDING_LEN)->label('(=) A Pagar')->result(
            \kontas\util\number::format($gasto - $pago)
    );

    $input = $climate->input('O que você deseja fazer?');
    $climate->inline('1')->tab()->out('Lançar o valor integral de ' . \kontas\util\number::format($gasto - $pago));
    $climate->inline('2')->tab()->out('Selecionar os gastos para pagar');
    $climate->inline('9')->tab()->out('Sair');
    $input->accept([1, 2, 9]);
    $input->strict();
    $choice = $input->prompt();

    if ($choice == 9) {
        $climate->info('Abortando...');
        exit(0);
    }

    $data = \kontas\util\date::parseInput(\kontas\io\generic::askVencimento('Data do pagamento [ddmmaaaa]:'));
    $observacao = \kontas\io\generic::askDescricao('Observação (opcional):');

    if ($choice == 1) {

        foreach ($dados['despesas'] as $keyDespesa => $despesa) {
            foreach ($despesa['gasto'] as $keyGasto => $gasto) {
                if ($gasto['mp'] === $mp) {
                    \kontas\ds\despesa::pagar($periodo, $keyDespesa, $keyGasto, $gasto['valor'], $data, $observacao);
                }
            }
        }
    }

    if ($choice == 2) {
        $lista = [];
        foreach ($dados['despesas'] as $keyDespesa => $despesa) {
            foreach ($despesa['gasto'] as $keyGasto => $gasto) {
                if ($gasto['mp'] === $mp) {
                    $pago = 0;
                    foreach ($gasto['pagamento'] as $item) {
                        $pago += $item['valor'];
                    }
                    if ($pago < $gasto['valor']) {
                        $lista[] = [
                            'despesa' => $keyDespesa,
                            'gasto' => $keyGasto,
                            'descricao' => $despesa['descricao'],
                            'parcela' => $despesa['parcela'],
                            'totalParcelas' => $despesa['totalParcelas'],
                            'credor' => $gasto['credor'],
                            'valor' => $gasto['valor'] - $pago,
                            'data' => \kontas\util\date::format($gasto['data'])
                        ];
                    }
                }
            }
        }
    }
//    print_r($lista);exit();

    $climate->info('Gastos para selecionar:');

    foreach ($lista as $key => $item) {
        $climate->green()->inline($key)->tab()->out($item['descricao']);
        $climate->tab()->inline('Credor:')->tab()->out($item['credor']);
        $climate->tab()->inline('Parcelas:')->tab()->out("{$item['parcela']}/{$item['totalParcelas']}");
        $climate->tab()->inline('Data:')->tab()->out($item['data']);
        $climate->tab()->inline('Valor:')->tab()->out(
                \kontas\util\number::format($item['valor'])
        );
    }

    $selecao = [];
    $input = $climate->input('Escolha [apenas ENTER para encerrar a seleção]:');
    $input->accept(array_merge([''], array_keys($lista)));
    $input->strict();
    while (true) {
        $escolha = $input->prompt();
        if ($escolha === '') {
            break;
        }
        $selecao[] = $escolha;
    }
//    print_r($selecao);
    
    foreach ($selecao as $item){
        $keyDespesa = $lista[$item]['despesa'];
        $keyGasto = $lista[$item]['gasto'];
        $valor = $lista[$item]['valor'];
        \kontas\ds\despesa::pagar($periodo, $keyDespesa, $keyGasto, $valor, $data, $observacao);
    }


    $climate->info('Despesas pagas');
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}