<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cadastra uma nova previsão da receita...');

    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());

    $descricao = \kontas\io\generic::askDescricao();

    $origem = \kontas\io\origem::select();

    $devedor = \kontas\io\generic::askDevedor();

    $cc = \kontas\io\cc::select();

    $vencimento = \kontas\io\generic::askVencimento();
    if ($vencimento === '') {
        $vencimento = \kontas\util\date::lastDayOfMonth($periodo);
    } else {
        $vencimento = \kontas\util\date::parseInput($vencimento);
    }

    $agrupador = \kontas\io\generic::askAgrupador();

    $valor = \kontas\io\generic::askValor();

    $salvar = \kontas\io\receita::confirm([
                'periodo' => $periodo,
                'descricao' => $descricao,
                'origem' => $origem,
                'devedor' => $devedor,
                'cc' => $cc,
                'vencimento' => $vencimento,
                'agrupador' => $agrupador,
                'valor' => $valor
    ]);
    if ($salvar === false) {
        $climate->error('Abortando...');
        exit(0);
    }

    $key = \kontas\ds\receita::addReceita($periodo, $descricao, $origem, $devedor, $cc, $vencimento, $agrupador, 1, 1, $valor, date('Y-m-d'), 'Previsão inicial');

    $climate->info('Registro criado:');
    $data = \kontas\ds\periodo::load($periodo);
    $data = $data['receitas'][$key];

    \kontas\io\receita::resume($periodo, $data);
    
    $confirm = $climate->confirm('Deseja lançar o recebimento?');
    if($confirm->confirmed()){
        $climate->out("Valor [####.##] ou ENTER para receber $valor:");
        $input = $climate->input('>');
        $confirmarValor = $input->prompt();
        if($confirmarValor !== ''){
            $valor = $confirmarValor;
        }
        
        $recebidoEm = \kontas\io\generic::askVencimento('Data do recebimento [ddmmaaaaa] ou ENTER para a data atual:');
        if($recebidoEm === ''){
            $recebidoEm = date('Y-m-d');
        }else{
            $recebidoEm = \kontas\util\date::parseInput($recebidoEm);
        }
        
        $observacao = \kontas\io\generic::askDescricao('Observação (opcional):');
        
        \kontas\ds\receita::receber($periodo, $key, $recebidoEm, $valor, $observacao);
        $climate->info('Recebimento salvo.');
    }

    $input = $climate->confirm("Deseja repetir no próximo período?");
    while ($input->confirmed()) {
        $periodo = \kontas\util\periodo::periodoPosterior($periodo);
        $proximoVencimento = \kontas\util\date::proximoVencimento($vencimento);

        $vencimento = \kontas\io\generic::askVencimento("Vencimento [ddmmaaaa] ou ENTER para $proximoVencimento:");
        if ($vencimento === '') {
            $vencimento = $proximoVencimento;
        } else {
            $vencimento = \kontas\util\date::parseInput($vencimento);
        }

        $proximoValor = \kontas\io\generic::askValor("Valor [####.##] ou ENTER para $valor:");
        if ($proximoValor !== '') {
            $valor = $proximoValor;
        }

        $salvar = \kontas\io\receita::confirm([
                    'periodo' => $periodo,
                    'descricao' => $descricao,
                    'origem' => $origem,
                    'devedor' => $devedor,
                    'cc' => $cc,
                    'vencimento' => $vencimento,
                    'agrupador' => $agrupador,
                    'valor' => $valor
        ]);
        if ($salvar === false) {
            $climate->error('Abortando...');
            exit(0);
        }

        $key = \kontas\ds\receita::addReceita($periodo, $descricao, $origem, $devedor, $cc, $vencimento, $agrupador, 1, 1, $valor, date('Y-m-d'), 'Previsão inicial');

        $climate->info('Registro criado:');
        $data = \kontas\ds\periodo::load($periodo);
        $data = $data['receitas'][$key];

        \kontas\io\receita::resume($periodo, $data);
        
        $input = $climate->confirm("Deseja repetir no próximo período?");
    }
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}