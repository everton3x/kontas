<?php

use Kontas\Exception\FailException;
use Kontas\IO\AplicacaoIO;
use Kontas\IO\DespesaIO;
use Kontas\IO\IO;
use Kontas\Recordset\DespesaRecord;
use Kontas\Recordset\PeriodoRecord;
use Kontas\Repo\AplicacoesRepo;
use Kontas\Util\Periodo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try {
    $cli = new CLImate();

    $cli->info('Cria uma nova previsão de despesa...');

    $periodo = Periodo::parseInput(IO::input('Período [MMAAAA]:'));

    $descricao = IO::input('Descrição:');
    
    $aplicacoesRepo = new AplicacoesRepo();
    $aplicacoesIO = new AplicacaoIO($aplicacoesRepo);
    $aplicacao = $aplicacoesRepo->record($aplicacoesIO->select(true))['nome'];
    
    $projetosRepo = new Kontas\Repo\ProjetosRepo();
    $projetoIO = new Kontas\IO\ProjetoIO($projetosRepo);
    $projeto = $projetosRepo->record($projetoIO->select(true))['nome'];

    $agrupador = IO::input('Agrupador (opcional):');

    $valor = (float) IO::input('Valor [0000.00]:');

    $rsPeriodo = new PeriodoRecord($periodo);
    $rsDespesa = new DespesaRecord($rsPeriodo);
    $ioDespesa = new DespesaIO($rsPeriodo);

    $data = $rsDespesa->novaPrevisaoInicial($periodo, $descricao, $aplicacao, $projeto, $agrupador, $valor, 1, 1);

    $indexDespesa = $rsPeriodo->adicionaPrevisaoDespesa($data);

    $cli->info('Registro salvo:');
    $ioDespesa->detalhes($indexDespesa);

} catch (FailException $ex) {
    $cli->error($ex->getMessage());
    exit($ex->getCode());
} catch (Exception $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit($ex->getCode());
}

exit(0);
