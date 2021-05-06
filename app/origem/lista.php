<?php

use Kontas\Comando;
use Kontas\Origem;
use League\CLImate\CLImate;

require_once 'vendor/autoload.php';

$climate = new CLImate();

$climate->description('Mostra a lista das origens da receita.');

try {
    $climate->arguments->add([
        'ativos' => [
            'prefix' => 'a',
            'longPrefix' => 'ativos',
            'description' => 'Mostra apenas os ativos',
            'noValue' => true
        ],
        'inativos' => [
            'prefix' => 'i',
            'longPrefix' => 'inativos',
            'description' => 'Mostra apenas os inativos',
            'noValue' => true
        ]
    ]);

    Comando::parseArgs($climate);

    $index = $climate->arguments->get('index');

    $data = Origem::lista();

    if ($climate->arguments->defined('ativos')) {
        $data = Origem::listaAtivos();
    }

    if ($climate->arguments->defined('inativos')) {
        $data = Origem::listaInativos();
    }

    $tabela = [];
    foreach ($data as $index => $item) {
        $tabela[] = [
            '#' => $index,
            'Nome' => $item['nome'],
            'Descrição' => $item['descricao'],
            'Ativo' => $item['ativo'],
        ];
    }

    $climate->red()->flank('Origens cadastradas');
    $climate->table($tabela);
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->whisper($ex->getTraceAsString());
    exit(126);
}