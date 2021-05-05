<?php

use Kontas\Comando;
use Kontas\Origem;
use League\CLImate\CLImate;

require_once 'vendor/autoload.php';

$climate = new CLImate();

$climate->description('Ativa/desativa uma origem de receita.');

try {
    $climate->arguments->add([
        'index' => [
            'prefix' => 'i',
            'longPrefix' => 'index',
            'description' => 'Índice da origem da receita',
            'required' => true,
            'castTo' => 'int'
        ],
        'ativo' => [
            'prefix' => 'a',
            'longPrefix' => 'ativo',
            'description' => 'Usado para ativar.',
            'noValue' => true
        ],
        'inativo' => [
            'prefix' => 'i',
            'longPrefix' => 'inativo',
            'description' => 'Usado para inativar.',
            'noValue' => true
        ],
    ]);

    Comando::parseArgs($climate);
    
    $index = $climate->arguments->get('index');
    
    if($climate->arguments->defined('ativo') && $climate->arguments->defined('inativo')){
        throw new Exception('Você não pode usar --ativo e --inativo ao mesmo tempo.');
    }
    
    if(!$climate->arguments->defined('ativo') && !$climate->arguments->defined('inativo')){
        throw new Exception('Você deve usar --ativo ou --inativo.');
    }
    
    if($climate->arguments->defined('ativo')){
        $ativo = true;
    }
    
    if($climate->arguments->defined('inativo')){
        $ativo = false;
    }
    
    Origem::ativo($index, $ativo);
    
    $climate->info("Status alterado.");
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->whisper($ex->getTraceAsString());
    exit(126);
}