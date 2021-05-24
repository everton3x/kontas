<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Altera a previsão da despesa...');

    $periodo = \kontas\util\periodo::parseInput(\kontas\io\periodo::askPeriodo());

    $key = \kontas\io\despesa::choice($periodo);
    
    $climate->info("Despesa selecionada:");
    \kontas\io\despesa::resume($periodo, $key);
    
    $valor = \kontas\io\generic::askValor('Informe o valor da alteração [-][####.##]:');
    
    $observacao = \kontas\io\generic::askDescricao('Observação (opcional):');
    
    \kontas\ds\despesa::alteraPrevisaoDespesa($periodo, $key, $valor, date('Y-m-d'), $observacao);

    $climate->info("Despesa alterada:");
    \kontas\io\despesa::resume($periodo, $key);

} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}