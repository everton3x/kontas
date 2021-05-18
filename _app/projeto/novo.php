<?php

use Kontas\Exception\FailException;
use Kontas\IO\ProjetoIO;
use Kontas\IO\IO;
use Kontas\Repo\ProjetosRepo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Cadastrando novo projeto da despesa...');
    
    $nome = IO::input('Nome:');
    $descricao = IO::input('Descrição (opcional):');
    $ativo = true;
    
    if(mb_strlen($nome) === 0){
        throw new FailException('Nome é obrigatório.');
    }
    
    $repo = new ProjetosRepo();
    $index = $repo->add($nome, $descricao, $ativo);
    
    $cli->info("Registro salvo.");
    
    $io = new ProjetoIO($repo);
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