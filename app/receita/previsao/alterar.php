<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Altera o valor previsto para uma receita...');

    $periodo = kontas\util\periodo::parseInput(kontas\io\periodo::askPeriodo());

    $keyReceita = \kontas\io\receita::choice($periodo);

    $valor = \kontas\io\generic::askValor();

    $observacao = \kontas\io\generic::askDescricao('Observação (opcional):');
    
    $key = \kontas\ds\receita::alterarPrevisao($periodo, $keyReceita, date('Y-m-d'), $valor, $observacao);
    
    
    $periodoData = \kontas\ds\periodo::load($periodo);
    $periodoData = $periodoData['receitas'][$keyReceita];

    \kontas\io\receita::resume($periodo, $periodoData);
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}