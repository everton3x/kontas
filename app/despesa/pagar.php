<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Paga uma despesa...');

    $periodo = \kontas\util\periodo::parseInput(\kontas\io\periodo::askPeriodo());

    $despesa = \kontas\io\despesa::choice($periodo);

    $climate->info("Despesa selecionada:");
    \kontas\io\despesa::resume($periodo, $despesa);
    $climate->br();
    $gasto = \kontas\io\despesa::choiceGasto($periodo, $despesa);
    
    $valor = \kontas\io\generic::askValor();
    $data = \kontas\util\date::parseInput(\kontas\io\generic::askVencimento('Data do pagamento [ddmmaaaa]:'));
    $observacao = \kontas\io\generic::askDescricao('Observação (opcional):');

    \kontas\ds\despesa::pagar($periodo, $despesa, $gasto, $valor, $data, $observacao);
    
    $climate->info("Despesa paga:");
    \kontas\io\despesa::resume($periodo, $despesa);
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}