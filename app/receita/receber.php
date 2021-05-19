<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Recebe uma receita...');

    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());

    $keyReceita = \kontas\io\receita::choice($periodo);

    $data = \kontas\io\generic::askVencimento('Data do recebimento [ddmmaaaa]:');
    if ($data === '') {
        $data = date('Y-m-d');
    } else {
        $data = \kontas\util\date::parseInput($data);
    }

    $valor = \kontas\io\generic::askValor();

    $observacao = \kontas\io\generic::askDescricao('Observação (opcional):');

    if (\kontas\io\receita::confirmRecebimento($data, $valor, $observacao) === false) {
        $climate->error('Abortando...');
        exit(0);
    }

    $periodoData = \kontas\ds\periodo::load($periodo);
    $receita = $periodoData['receitas'][$keyReceita];
    $previsto = 0;
    $recebido = 0;
    foreach ($receita['previsao'] as $item) {
        $previsto += $item['valor'];
    }
    foreach ($receita['recebimento'] as $item) {
        $recebido += $item['valor'];
    }
    $recebido += $valor;
    if ($recebido > $previsto) {
        $climate->yellow()->out(
                sprintf(
                        "O valor recebido %s irá ultrapassar o valor previsto %s.",
                        \kontas\util\number::format($recebido),
                        \kontas\util\number::format($previsto)
        ));
        $confirm = $climate->confirm('Deseja complementar a previsão?');
        
        if($confirm->confirmed()){
            $diferenca = $recebido - $previsto;
            \kontas\ds\receita::alterarPrevisao($periodo, $keyReceita, $data, $diferenca, 'Atualização automática');
            $climate->info('Previsão atualizada.');
        }
    }
    
    $key = \kontas\ds\receita::receber($periodo, $keyReceita, $data, $valor, $observacao);
    
    
    $periodoData = \kontas\ds\periodo::load($periodo);
    $periodoData = $periodoData['receitas'][$keyReceita];

    \kontas\io\receita::resume($periodo, $periodoData);
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}