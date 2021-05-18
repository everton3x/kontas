<?php

use Kontas\Exception\FailException;
use Kontas\IO\IO;
use Kontas\IO\PeriodoIO;
use Kontas\IO\ReceitaIO;
use Kontas\Recordset\PeriodoRecord;
use Kontas\Recordset\ReceitaRecord;
use Kontas\Util\Date;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Receber uma receita prevista...');
    
    $periodo = PeriodoIO::select();
    $rsPeriodo = new PeriodoRecord($periodo);
    
    $ioReceita = new ReceitaIO($rsPeriodo);
    $indexReceita = $ioReceita->select();
    
    $data = IO::input('Data [ddmmaaaa]:');
    $data = Date::parseInput($data);
    
    $valor = IO::input('Valor [0000.00]:');
    
    $observacao = IO::input('Observação (opcional):');
    
    $rsReceita = new ReceitaRecord();
    
    $recebimento = $rsReceita->novoRecebimento($data, $valor, $observacao);
    
    $rsPeriodo->adicionaRecebimento($indexReceita, $recebimento);
    
    $cli->info('Registro salvo');
    $ioReceita->detalhes($indexReceita);
    
    
} catch (FailException $ex) {
    $cli->error($ex->getMessage());
    exit($ex->getCode());
} catch (Exception $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit($ex->getCode());
}

exit(0);