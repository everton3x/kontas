<?php

use Kontas\Exception\FailException;
use Kontas\IO\IO;
use Kontas\IO\MpIO;
use Kontas\Repo\MpRepo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Cadastrando novo meio d epagamento...');
    
    $nome = IO::input('Nome:');
    $descricao = IO::input('Descrição (opcional):');
    $autopagar = IO::confirm('Autopagar?');
    $ativo = true;
    
    if(mb_strlen($nome) === 0){
        throw new FailException('Nome é obrigatório.');
    }
    
    $repo = new MpRepo();
    $index = $repo->add($nome, $descricao, $ativo, $autopagar);
    
    $cli->info("Registro salvo.");
    
    $io = new MpIO($repo);
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