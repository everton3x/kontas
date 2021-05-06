<?php

use Kontas\Comando;
use Kontas\Origem;
use League\CLImate\CLImate;

require_once 'vendor/autoload.php';

$climate = new CLImate();

$climate->description('Altera uma origem da receita.');

try {
    $climate->arguments->add([
        'index' => [
            'prefix' => 'i',
            'longPrefix' => 'index',
            'description' => 'Índice da origem da receita',
            'required' => true,
            'castTo' => 'int'
        ],
        'nome' => [
            'prefix' => 'n',
            'longPrefix' => 'nome',
            'description' => 'Novo nome',
            'required' => false,
            'castTo' => 'string'
        ],
        'descricao' => [
            'prefix' => 'd',
            'longPrefix' => 'desc',
            'description' => 'Nova descrição.',
            'required' => false,
            'castTo' => 'string'
        ],
    ]);

    Comando::parseArgs($climate);
    
    $index = $climate->arguments->get('index');
    $nome = null;
    $descricao = null;
    
    if(!$climate->arguments->defined('nome') && !$climate->arguments->defined('descricao')){
        throw new Exception("Você precisa indicar ou --nome, ou --desc, ou ambos!");
    }
    
    if($climate->arguments->defined('nome')){
        $nome = $climate->arguments->get('nome');
    }
    if($climate->arguments->defined('descricao')){
        $descricao = $climate->arguments->get('descricao');
    }
    
    Origem::altera($index, $nome, $descricao);
    
    $climate->info("Origem da receita $index alterada.");
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->whisper($ex->getTraceAsString());
    exit(126);
}