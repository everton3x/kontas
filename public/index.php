<?php

require_once '../vendor/autoload.php';

$periodo = date('Y-m');
if (key_exists('periodo', $_POST)) $periodo = $_POST['periodo'];
if (key_exists('periodo', $_GET)) $periodo = $_GET['periodo'];

carregaTemplate('resumo', [
    'mesAnterior' => periodoAnterior($periodo),
    'mesAtual' => periodo2DateTime($periodo),
    'mesPosterior' => proximoPeriodo($periodo)
]);
