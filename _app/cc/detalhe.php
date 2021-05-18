<?php

use Kontas\Exception\FailException;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try{
    $cli = new CLImate();
    
    $cli->info('Mostra detalhes de um centro de custo...');
    
    $repo = new Kontas\Repo\CcRepo();
    $io = new Kontas\IO\CcIO($repo);
    
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