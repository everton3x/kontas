<?php

use Kontas\Comando;
use Kontas\Origem;
use League\CLImate\CLImate;

require_once 'vendor/autoload.php';

$climate = new CLImate();

$climate->description('Cria uma nova origem de receita.');

try {
    $climate->arguments->add([
        'nome' => [
            'prefix' => 'n',
            'longPrefix' => 'nome',
            'description' => 'Nome da origem da receita',
            'required' => true,
            'castTo' => 'string'
        ],
        'descricao' => [
            'prefix' => 'd',
            'longPrefix' => 'desc',
            'description' => 'Descrição opcional para a origem de receita.',
            'required' => false,
            'castTo' => 'string'
        ],
    ]);

    Comando::parseArgs($climate);
    
    $nome = $climate->arguments->get('nome');
    $descricao = $climate->arguments->get('descricao');
    
    Origem::adiciona($nome, $descricao);
    
    $climate->info("Origem $nome ($descricao) foi salva.");
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->whisper($ex->getTraceAsString());
    exit(126);
}