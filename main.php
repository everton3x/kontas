<?php

// Ponto de entrada do programa

require 'vendor/autoload.php';

try {
    $program = new Kontas\App\KProgram(new Kontas\App\KEnvironment());

    $abrirPeriodo = new \Kontas\Routine\Periodo\AbrirPeriodo($program);
    $detalharPeriodo = new \Kontas\Routine\Periodo\DetalharPeriodo($program);
    $fecharPeriodo = new \Kontas\Routine\Periodo\FecharPeriodo($program);
    $listarPeriodos = new \Kontas\Routine\Periodo\ListarPeriodos($program);
    $reabrirPeriodo = new \Kontas\Routine\Periodo\ReabrirPeriodo($program);
    $resumirPeriodo = new \Kontas\Routine\Periodo\ResumirPeriodo($program);
    $gerenciarPeriodos = new \Kontas\Routine\GerenciarPeriodos($program);
    $gerenciarPeriodos
            ->registerSubRoutine($abrirPeriodo)
            ->registerSubRoutine($fecharPeriodo)
            ->registerSubRoutine($reabrirPeriodo)
            ->registerSubRoutine($listarPeriodos)
            ->registerSubRoutine($resumirPeriodo)
            ->registerSubRoutine($detalharPeriodo);
    
    $gerenciarOrigens = new \Kontas\Routine\GerenciarOrigens($program);
    
    $gerenciarAplicacoes = new \Kontas\Routine\GerenciarAplicacoes($program);
    
    $gerenciarCentrosDeCustos = new Kontas\Routine\GerenciarCentrosDeCustos($program);
    
    $gerenciarMeiosDePagamento = new \Kontas\Routine\GerenciarMeiosDePagamento($program);
    
    
    $gerenciarCadastros = new Kontas\Routine\GerenciarCadastros($program);
    $gerenciarCadastros
            ->registerSubRoutine($gerenciarOrigens)
            ->registerSubRoutine($gerenciarAplicacoes)
            ->registerSubRoutine($gerenciarCentrosDeCustos)
            ->registerSubRoutine($gerenciarMeiosDePagamento);

    $mainMenu = new \Kontas\Routine\MainMenu($program);
    $mainMenu
            ->registerSubRoutine($gerenciarPeriodos)
            ->registerSubRoutine($gerenciarCadastros);

    $program->entryPoint($mainMenu);
    $program->run();
} catch (Exception $ex) {
    echo $ex->getMessage(), PHP_EOL;
//    echo $ex->getTraceAsString(), PHP_EOL;
    exit($ex->getCode());
}