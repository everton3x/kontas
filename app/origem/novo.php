<?php

use Kontas\Exception\FailException;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Cadastrando nova origem da receita...');
    
    $nome = \Kontas\IO\IO::input('Nome:');
    $descricao = \Kontas\IO\IO::input('Descrição (opcional):');
    $ativo = true;
    
    if(mb_strlen($nome) === 0){
        throw new FailException('Nome é obrigatório.');
    }
    
    $repo = new Kontas\Repo\OrigensRepo();
    $index = $repo->add($nome, $descricao, $ativo);
    
    $cli->info("Registro salvo.");
    
    $io = new Kontas\IO\OrigemIO($repo);
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