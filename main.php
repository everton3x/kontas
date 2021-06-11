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
    
    $adicionarOrigem = new \Kontas\Routine\Origem\Adicionar($program);
    $alterarOrigem = new \Kontas\Routine\Origem\Alterar($program);
    $listarOrigens = new \Kontas\Routine\Origem\Listar($program);
    $gerenciarOrigens = new \Kontas\Routine\GerenciarOrigens($program);
    $gerenciarOrigens
            ->registerSubRoutine($adicionarOrigem)
            ->registerSubRoutine($alterarOrigem)
            ->registerSubRoutine($listarOrigens)
            ;
    
    $gerenciarAplicacoes = new \Kontas\Routine\GerenciarAplicacoes($program);
    
    $gerenciarCentrosDeCustos = new Kontas\Routine\GerenciarCentrosDeCustos($program);
    
    $gerenciarMeiosDePagamento = new \Kontas\Routine\GerenciarMeiosDePagamento($program);
    
    $gerenciarProjetos = new \Kontas\Routine\GerenciarProjetos($program);
    
    
    $gerenciarCadastros = new Kontas\Routine\GerenciarCadastros($program);
    $gerenciarCadastros
            ->registerSubRoutine($gerenciarOrigens)
            ->registerSubRoutine($gerenciarAplicacoes)
            ->registerSubRoutine($gerenciarCentrosDeCustos)
            ->registerSubRoutine($gerenciarProjetos)
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