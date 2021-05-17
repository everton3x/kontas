<?php

use Kontas\Exception\FailException;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Altera o status de uma aplicação da despesa...');
    
    $repo = new Kontas\Repo\AplicacoesRepo();
    $io = new Kontas\IO\AplicacaoIO($repo);
    
    $index = $io->select();
    $io->detail($index);
    
    $status = \Kontas\IO\IO::choice([1=>'Ativo', 0=>'Inativo']);
    
    $repo->changeStatus($index, $status);
    
    $cli->info("Registro salvo.");
    $io->detail($index);
    
    
} catch (FailException $ex) {
    $cli->error($ex->getMessage());
    exit($ex->getCode());
} catch (Exception $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit($ex->getCode());
}

exit(0);