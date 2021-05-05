<?php

use Kontas\Comando;
use Kontas\Origem;
use League\CLImate\CLImate;

require_once 'vendor/autoload.php';

$climate = new CLImate();

$climate->description('Mostra os detalhes de uma origem da receita.');

try {
    $climate->arguments->add([
        'index' => [
            'prefix' => 'i',
            'longPrefix' => 'index',
            'description' => 'Índice da origem da receita',
            'required' => true,
            'castTo' => 'int'
        ]
    ]);

    Comando::parseArgs($climate);
    
    $index = $climate->arguments->get('index');
    
    $data = Origem::consulta($index);
    
    $tabela = [
        [
            'Nome' => $data['nome'],
            'Descrição' => $data['descricao'],
            'Ativo' => $data['ativo'],
        ]
    ];
    $climate->red()->flank('Detalhe da origem');
    $climate->table($tabela);
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->whisper($ex->getTraceAsString());
    exit(126);
}