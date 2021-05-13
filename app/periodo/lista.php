<?php

use Kontas\Exception\FailException;
use Kontas\IO\PeriodoIO;
use Kontas\Repo\PeriodosRepo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Lista os períodos...');
    
    $repo = new PeriodosRepo();
    $periodos = $repo->lista();
    
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