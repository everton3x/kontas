<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Calcula os resultados dos períodos abertos...');

    
    $fechados = \kontas\ds\periodo::listClosed();
    $fechados = array_keys($fechados);
    sort($fechados);
    $ultimoFechado = array_pop($fechados);
    $data = \kontas\ds\periodo::load($ultimoFechado);
    $anterior = $data['resultados']['acumulado'];
    
    $abertos = \kontas\ds\periodo::listOpened();
    $abertos = array_keys($abertos);
    sort($abertos);
    foreach ($abertos as $periodo){
        $climate->bold()->green()->out(
                \kontas\util\periodo::format($periodo)
        );
        
        $data = \kontas\ds\periodo::load($periodo);
        $data = \kontas\ds\periodo::calcResultadosFor($data);
        \kontas\ds\periodo::save($data);
        
        $climate->inline('Anterior:')->tab()->out(
                \kontas\util\number::format($data['resultados']['anterior'])
        );
        $climate->inline('Período:')->tab()->out(
                \kontas\util\number::format($data['resultados']['periodo'])
        );
        $climate->inline('Acumulado:')->tab()->out(
                \kontas\util\number::format($data['resultados']['acumulado'])
        );
    }

} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}