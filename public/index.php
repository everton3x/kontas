<?php

require_once '../vendor/autoload.php';

$periodo = filter_input(INPUT_GET, 'periodo', FILTER_VALIDATE_REGEXP, [
    'options' => [
        'regexp' => '/[0-9]{6}/',
        'default' => date('Ym')
    ]
]);

carregaTemplate('resumo', [
    'mesAnterior' => periodoAnterior($periodo),
    'mesAtual' => periodo2DateTime($periodo),
    'mesPosterior' => proximoPeriodo($periodo)
]);
