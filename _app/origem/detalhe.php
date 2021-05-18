<?php

use Kontas\Exception\FailException;
use Kontas\IO\OrigemIO;
use Kontas\Repo\OrigensRepo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Mostra detalhes de uma origem da receita...');
    
    $repo = new OrigensRepo();
    $io = new OrigemIO($repo);
    
    $index = $io->select();
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