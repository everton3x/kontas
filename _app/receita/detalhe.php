<?php

use Kontas\Exception\FailException;
use Kontas\IO\IO;
use Kontas\IO\ReceitaIO;
use Kontas\Recordset\PeriodoRecord;
use Kontas\Recordset\ReceitaRecord;
use Kontas\Util\Periodo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    $periodo = IO::input('Período:');
    $rsPeriodo = new PeriodoRecord(Periodo::parseInput($periodo));
    $rsReceita = new ReceitaRecord($rsPeriodo);
    $ioReceita = new ReceitaIO($rsPeriodo);
    
    $ioReceita->detalhes($ioReceita->select());
    
    
    
    
} catch (FailException $ex) {
    $cli->error($ex->getMessage());
    exit($ex->getCode());
} catch (Exception $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit($ex->getCode());
}

exit(0);