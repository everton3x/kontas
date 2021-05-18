<?php

use Kontas\Exception\FailException;
use Kontas\IO\IO;
use Kontas\IO\PeriodoIO;
use Kontas\Util\Periodo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Cria um novo período...');
    
    $periodo = IO::input('Período [MMAAAA]:');
    $copiar = IO::input('Período para copiar (opcional) [MMAAAA]:');
    
    if(mb_strlen($copiar) > 0){
        //@todo
    }
    
    $periodo = Periodo::parseInput($periodo);
    $rs = Periodo::criar($periodo);
    $io = new PeriodoIO($rs);
    $cli->info("Período salvo.");
    $io->resume();
    
    
} catch (FailException $ex) {
    $cli->error($ex->getMessage());
    exit($ex->getCode());
} catch (Exception $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit($ex->getCode());
}

exit(0);