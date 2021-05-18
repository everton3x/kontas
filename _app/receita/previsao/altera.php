<?php

use Kontas\Exception\FailException;
use Kontas\IO\CcIO;
use Kontas\IO\IO;
use Kontas\IO\OrigemIO;
use Kontas\IO\ReceitaIO;
use Kontas\Recordset\PeriodoRecord;
use Kontas\Recordset\ReceitaRecord;
use Kontas\Repo\CcRepo;
use Kontas\Repo\OrigensRepo;
use Kontas\Util\Date;
use Kontas\Util\Periodo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Altera um previsão de receita...');
    
    $periodo = Periodo::parseInput(IO::input('Período [MMAAAA]:'));
    
    $rsPeriodo = new PeriodoRecord($periodo);
    $rsReceita = new ReceitaRecord($rsPeriodo);
    $ioReceita = new ReceitaIO($rsPeriodo);
    
    $index = $ioReceita->select();
    
    $valor = (float) IO::input('Valor [-][0000.00]:');
    $observacao = IO::input('Observação (opcional):');
    
    $data = $rsReceita->novaAlteracaoPrevisao($valor, $observacao);
    
    $rsPeriodo->atualizaPrevisaoReceita($index, $data);
    
    
    $cli->info('Registro salvo:');
    $ioReceita->detalhes($index);
    
    
    
    
} catch (FailException $ex) {
    $cli->error($ex->getMessage());
    exit($ex->getCode());
} catch (Exception $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit($ex->getCode());
}

exit(0);