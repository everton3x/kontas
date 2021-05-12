<?php

use Kontas\Exception\FailException;
use Kontas\IO\AplicacaoIO;
use Kontas\IO\IO;
use Kontas\Repo\AplicacoesRepo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Cadastrando nova aplicação da despesa...');
    
    $nome = IO::input('Nome:');
    $descricao = IO::input('Descrição (opcional):');
    $ativo = true;
    
    if(mb_strlen($nome) === 0){
        throw new FailException('Nome é obrigatório.');
    }
    
    $repo = new AplicacoesRepo();
    $index = $repo->add($nome, $descricao, $ativo);
    
    $cli->info("Registro salvo.");
    
    $io = new AplicacaoIO($repo);
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