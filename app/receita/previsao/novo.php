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
use Kontas\Util\Date;
use Kontas\Util\Periodo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try {
    $cli = new CLImate();

    $cli->info('Cria uma nova previsão de receita...');

    $periodo = Periodo::parseInput(IO::input('Período [MMAAAA]:'));

    $descricao = IO::input('Descrição:');

    $origensRepo = new OrigensRepo();
    $origemIO = new OrigemIO($origensRepo);
    $origem = $origensRepo->record($origemIO->select(true))['nome'];

    $devedor = IO::input('Devedor:');

    $ccRepo = new CcRepo();
    $ccIO = new CcIO($ccRepo);
    $cc = $ccRepo->record($ccIO->select(true))['nome'];

    $vencimento = IO::input('Vencimento (opcional) [ddmmaaaa]:');
    if (mb_strlen($vencimento) > 0) {
        $vencimento = Date::parseInput($vencimento);
    } else {
        $vencimento = Date::ultimoDiaDoMes($periodo);
    }

    $agrupador = IO::input('Agrupador (opcional):');

    $valor = (float) IO::input('Valor [0000.00]:');

    $autoreceber = IO::confirm('Lançar o recebimento?');

    $recebidoEm = null;
    if ($autoreceber) {
        $recebidoEm = Date::parseInput(IO::input('Recebido em [ddmmaaaa]:'));
    }

    $rsPeriodo = new PeriodoRecord($periodo);
    $rsReceita = new ReceitaRecord($rsPeriodo);
    $ioReceita = new ReceitaIO($rsPeriodo);

    $data = $rsReceita->novaPrevisaoInicial($periodo, $descricao, $origem, $devedor, $cc, $vencimento, $agrupador, $valor, 1, 1);

    $indexReceita = $rsPeriodo->adicionaPrevisaoReceita($data);

    if ($autoreceber) {
        $recebimento = $rsReceita->novoRecebimento($recebidoEm, $valor, 'Recebimento automático.');
        $rsPeriodo->adicionaRecebimento($indexReceita, $recebimento);
    }

    $cli->info('Registro salvo:');
    $ioReceita->detalhes($indexReceita);

    $repetir = IO::confirm("Deseja repetir para a próxima competência?");
    if ($repetir) {
        $loop = IO::input('Quantos meses mais?');
        if (is_numeric($loop) === false) {
            throw new FailException("Deves fornecer um número: $loop");
        }
        if ($loop <= 0) {
            throw new FailException("Deves fornecer um número maior que zero: $loop");
        }

        for ($i = 0; $i < $loop; $i++) {
            $periodo = Periodo::posterior($periodo);
            $vencimento = Date::proximoVencimento($vencimento);

            if (Periodo::existe($periodo)) {
                $rsPeriodo = new PeriodoRecord($periodo);
            } else {
                $rsPeriodo = Periodo::criar($periodo);
            }

            $rsReceita = new ReceitaRecord($rsPeriodo);
            $ioReceita = new ReceitaIO($rsPeriodo);

            $data = $rsReceita->novaPrevisaoInicial($periodo, $descricao, $origem, $devedor, $cc, $vencimento, $agrupador, $valor, 1, 1);

            $indexReceita = $rsPeriodo->adicionaPrevisaoReceita($data);

            $cli->info('Registro salvo:');
            $ioReceita->detalhes($indexReceita);
        }
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
