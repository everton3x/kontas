<?php

use Kontas\Exception\FailException;
use Kontas\IO\ReceitaIO;
use Kontas\Recordset\PeriodoRecord;
use Kontas\Recordset\ReceitaRecord;

require 'vendor/autoload.php';

try{
    $rsPeriodo = new PeriodoRecord('2021-06');
    $rsReceita = new ReceitaRecord($rsPeriodo);
    $ioReceita = new ReceitaIO($rsPeriodo);
    
    $ioReceita->detalhes(1);
    
    
    
    
} catch (FailException $ex) {
    $cli->error($ex->getMessage());
    exit($ex->getCode());
} catch (Exception $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit($ex->getCode());
}

exit(0);