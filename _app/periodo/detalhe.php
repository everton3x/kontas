<?php

use Kontas\Exception\FailException;
use Kontas\IO\IO;
use Kontas\IO\PeriodoIO;
use Kontas\Recordset\PeriodoRecord;
use Kontas\Util\Periodo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Mostra os detalhes do período...');
    
    $periodo = IO::input('Período [MMAAAA]:');
    
    
    $periodo = Periodo::parseInput($periodo);
    $rs = new PeriodoRecord($periodo);
    $io = new PeriodoIO($rs);
    $cli->info("Detalhes do período");
    $io->detalhe();
    
    
} catch (FailException $ex) {
    $cli->error($ex->getMessage());
    exit($ex->getCode());
} catch (Exception $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit($ex->getCode());
}

exit(0);