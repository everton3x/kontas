<?php

use Kontas\Exception\FailException;
use Kontas\IO\PeriodoIO;
use Kontas\Recordset\PeriodoRecord;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Receber uma receita prevista...');
    
    $periodo = PeriodoIO::select();
    $rsPeriodo = new PeriodoRecord($periodo);
    
    $ioReceita = new Kontas\IO\ReceitaIO($rsPeriodo);
    $indexReceita = $ioReceita->select();
    
    $data = \Kontas\IO\IO::input('Data [ddmmaaaa]:');
    $data = Kontas\Util\Date::parseInput($data);
    
    $valor = \Kontas\IO\IO::input('Valor [0000.00]:');
    
    $observacao = \Kontas\IO\IO::input('Observação (opcional):');
    
    $rsReceita = new \Kontas\Recordset\ReceitaRecord();
    
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