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
    $detalharOrigem = new \Kontas\Routine\Origem\Detalhar($program);
    $gerenciarOrigens = new \Kontas\Routine\GerenciarOrigens($program);
    $gerenciarOrigens
            ->registerSubRoutine($adicionarOrigem)
            ->registerSubRoutine($alterarOrigem)
            ->registerSubRoutine($listarOrigens)
            ->registerSubRoutine($detalharOrigem)
            ;
    
    $adicionarCentroDeCusto = new \Kontas\Routine\CentroDeCusto\Adicionar($program);
    $alterarCentroDeCusto = new Kontas\Routine\CentroDeCusto\Alterar($program);
    $listarCentroDeCustos = new Kontas\Routine\CentroDeCusto\Listar($program);
    $detalharCentroDeCusto = new Kontas\Routine\CentroDeCusto\Detalhar($program);
    $gerenciarCentrosDeCustos = new Kontas\Routine\GerenciarCentrosDeCustos($program);
    $gerenciarCentrosDeCustos
            ->registerSubRoutine($adicionarCentroDeCusto)
            ->registerSubRoutine($alterarCentroDeCusto)
            ->registerSubRoutine($listarCentroDeCustos)
            ->registerSubRoutine($detalharCentroDeCusto)
            ;
    
    $adicionarAplicacao = new \Kontas\Routine\Aplicacao\Adicionar($program);
    $alterarAplicacao = new Kontas\Routine\Aplicacao\Alterar($program);
    $listarAplicacoes = new Kontas\Routine\Aplicacao\Listar($program);
    $detalharAplicacao = new Kontas\Routine\Aplicacao\Detalhar($program);
    $gerenciarAplicacoes = new \Kontas\Routine\GerenciarAplicacoes($program);
    $gerenciarAplicacoes
            ->registerSubRoutine($adicionarAplicacao)
            ->registerSubRoutine($alterarAplicacao)
            ->registerSubRoutine($listarAplicacoes)
            ->registerSubRoutine($detalharAplicacao)
            ;
    
    $adicionarProjeto = new \Kontas\Routine\Projeto\Adicionar($program);
    $alterarProjeto = new Kontas\Routine\Projeto\Alterar($program);
    $listarProjetos = new Kontas\Routine\Projeto\Listar($program);
    $detalharProjeto = new Kontas\Routine\Projeto\Detalhar($program);
    $gerenciarProjetos = new \Kontas\Routine\GerenciarProjetos($program);
    $gerenciarProjetos
            ->registerSubRoutine($adicionarProjeto)
            ->registerSubRoutine($alterarProjeto)
            ->registerSubRoutine($listarProjetos)
            ->registerSubRoutine($detalharProjeto)
            ;
    
    $gerenciarMeiosDePagamento = new \Kontas\Routine\GerenciarMeiosDePagamento($program);
    
    
    
    $gerenciarCadastros = new Kontas\Routine\GerenciarCadastros($program);
    $gerenciarCadastros
            ->registerSubRoutine($gerenciarOrigens)
            ->registerSubRoutine($gerenciarCentrosDeCustos)
            ->registerSubRoutine($gerenciarAplicacoes)
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