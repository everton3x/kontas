<?php

use Kontas\Exception\FailException;
use Kontas\IO\PeriodoIO;
use Kontas\Repo\PeriodosRepo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Lista os períodos...');
    
    $tipo = \Kontas\IO\IO::choice([
        0 => 'Todos',
        1 => 'Abertos',
        2 => 'Fechados'
    ], 0);
    
    $repo = new PeriodosRepo();
    
    switch ($tipo){
        case 0:
            $periodos = $repo->lista();
            break;
        case 1:
            $periodos = $repo->listaAbertos();
            break;
        case 2:
            $periodos = $repo->listaFechados();
            break;
    }
    
    
    $cli->info("Períodos existentes");
    PeriodoIO::lista($periodos);
    
    
    
} catch (FailException $ex) {
    $cli->error($ex->getMessage());
    exit($ex->getCode());
} catch (Exception $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit($ex->getCode());
}

exit(0);