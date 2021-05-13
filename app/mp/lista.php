<?php

use Kontas\Exception\FailException;
use Kontas\IO\MpIO;
use Kontas\Repo\MpRepo;
use League\CLImate\CLImate;

require 'vendor/autoload.php';

try {
    $cli = new CLImate();

    $cli->info('Mostra a lista de meios de pagamento...');

    $repo = new MpRepo();
    $io = new MpIO($repo);

    $lista = $repo->listAtivos();
    foreach ($lista as $key => $value) {
        $nome[$key] = $value['nome'];
    }
    if (sizeof($lista) > 0) {
        array_multisort($nome, SORT_ASC, $lista);
        $cli->info('Registros ativos');
        foreach ($lista as $item) {
            $cli->out($item['nome']);
        }
        $cli->br();
    }

    $lista = $repo->listInativos();
    $nome = [];
    foreach ($lista as $key => $value) {
        $nome[$key] = $value['nome'];
    }
    if (sizeof($lista) > 0) {
        array_multisort($nome, SORT_ASC, $lista);
        $cli->info('Registros inativos');
        foreach ($lista as $item) {
            $cli->out($item['nome']);
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