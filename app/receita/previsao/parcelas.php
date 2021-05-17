<?php

use Kontas\Exception\FailException;
use Kontas\IO\CcIO;
use Kontas\IO\IO;
use Kontas\IO\OrigemIO;
use Kontas\IO\ReceitaIO;
use Kontas\Recordset\PeriodoRecord;
use Kontas\Recordset\ReceitaRecord;
use Kontas\Repo\CcRepo;
use Kontas\Repo\OrigensRepo;
use Kontas\Repo\PeriodosRepo;
use Kontas\Util\Date;
use Kontas\Util\Number;
use Kontas\Util\Periodo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try {
    $cli = new CLImate();

    $cli->info('Cria uma nova previsão de receita com parcelas...');

    $periodo = Periodo::parseInput(IO::input('Período inicial [MMAAAA]:'));

    $totalParcelas = IO::input('Total de parcelas:');
    if (is_numeric($totalParcelas) === false) {
        throw new FailException("Deves fornecer um número: $totalParcelas");
    }
    if ($totalParcelas <= 1) {
        throw new FailException("Deves fornecer um número maior que 1: $totalParcelas");
    }

    $descricao = IO::input('Descrição:');

    $origensRepo = new OrigensRepo();
    $origemIO = new OrigemIO($origensRepo);
    $origem = $origensRepo->record($origemIO->select(true))['nome'];

    $devedor = IO::input('Devedor:');

    $ccRepo = new CcRepo();
    $ccIO = new CcIO($ccRepo);
    $cc = $ccRepo->record($ccIO->select(true))['nome'];

    $vencimento = IO::input('Vencimento inicial (opcional) [ddmmaaaa]:');
    if (mb_strlen($vencimento) > 0) {
        $vencimento = Date::parseInput($vencimento);
    } else {
        $vencimento = Date::ultimoDiaDoMes($periodo);
    }

    $agrupador = IO::input('Agrupador:');
    if (mb_strlen($agrupador) == 0) {
        throw new FailException("Um agrupador deve ser fornecido.");
    }
    
    $periodoRepo = new PeriodosRepo();
    if ($periodoRepo->existeAgrupador($agrupador)) {
        throw new FailException("Um agrupador já existe.");
    }

    $valor = (float) IO::input('Valor da parcela [0000.00]:');

    for ($parcela = 1; $parcela <= $totalParcelas; $parcela++) {
        if (Periodo::existe($periodo)) {
            $rsPeriodo = new PeriodoRecord($periodo);
        } else {
            $rsPeriodo = Periodo::criar($periodo);
        }

        $rsReceita = new ReceitaRecord($rsPeriodo);
        $ioReceita = new ReceitaIO($rsPeriodo);

        $venc = IO::input("Vencimento padrão [" . Date::format($vencimento) . "]:");
        if (mb_strlen($venc) > 0) {
            $vencimento = Date::parseInput($venc);
        }
        $val = IO::input("Valor da parcela padrão [" . Number::format($valor) . "]:");
        if (mb_strlen($venc) > 0) {
            $valor = $val;
        }

        $data = $rsReceita->novaPrevisaoInicial($periodo, $descricao, $origem, $devedor, $cc, $vencimento, $agrupador, $valor, $parcela, $totalParcelas);

        $indexReceita = $rsPeriodo->adicionaPrevisaoReceita($data);

        $cli->info('Registro salvo:');
        $ioReceita->detalhes($indexReceita);

        $periodo = Periodo::posterior($periodo);
        $vencimento = Date::proximoVencimento($vencimento);
    }
} catch (FailException $ex) {
    $cli->error($ex->getMessage());
    exit($ex->getCode());
} catch (Exception $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit($ex->getCode());
}

exit(0);
