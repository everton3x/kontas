<?php

use Kontas\Exception\FailException;
use Kontas\IO\IO;
use Kontas\IO\MpIO;
use Kontas\Repo\MpRepo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Altera o autopagamento de um meio de pagamento...');
    
    $repo = new MpRepo();
    $io = new MpIO($repo);
    
    $index = $io->select();
    $io->detail($index);
    
    $autopagar = IO::choice([1=>'Ativo', 0=>'Inativo']);
    
    $repo->changeAutopagar($index, $autopagar);
    
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