<?php

use Kontas\Exception\FailException;
use Kontas\IO\CcIO;
use Kontas\IO\IO;
use Kontas\Repo\CcRepo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Cadastrando novo centro de custo...');
    
    $nome = IO::input('Nome:');
    $descricao = IO::input('Descrição (opcional):');
    $ativo = true;
    
    if(mb_strlen($nome) === 0){
        throw new FailException('Nome é obrigatório.');
    }
    
    $repo = new CcRepo();
    $index = $repo->add($nome, $descricao, $ativo);
    
    $cli->info("Registro salvo.");
    
    $io = new CcIO($repo);
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