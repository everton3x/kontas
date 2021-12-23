<?php

/**
 * Funções relacionadas à contabilidade
 */

function buscarContasContabeis(): PDOStatement
{
    // return consultarNoDb('SELECT * FROM planodecontas ORDER BY codigo ASC');
    $con = conexao();
    return $con->query('SELECT * FROM planodecontas ORDER BY codigo ASC');
}

function gerarCodigoDeTransacao(): string
{
    return sha1(microtime() . rand());
}

function buscarDadosDaContaContabil(string $codigo): array
{
    /*$stmt = consultarNoDb('SELECT * FROM planodecontas WHERE codigo = :codigo', [':codigo' => $codigo]);
    if ($stmt === false) return [];
    if($stmt->rowCount() === 0) return [];
    return $stmt->fetch(PDO::FETCH_ASSOC);*/
    $con = conexao();
    $stmt = $con->prepare('SELECT * FROM planodecontas WHERE codigo = :codigo');
    if ($stmt === false) return [];
    $stmt->execute([':codigo' => $codigo]);
    if ($stmt->rowCount() === 0) return [];
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function adicionarTransacao(string $id, DateTime $data, string $historico, array $lancamentos): array
{
    $result = [
        'success' => true
    ];

    //remove eventual lançamento sem conta contábil
    foreach ($lancamentos as $index => $item) {
        if ($item['contaContabil'] === '') unset($lancamentos[$index]);
    }

    //testa se o histórico não está vazio
    if (strlen($historico) === 0) {
        $result['success'] = false;
        $result['errors'][] = 'O histórico da transação não pode ser vazio.';
    }

    //testa se tem lançamentos
    if (sizeof($lancamentos) === []) {
        $result['success'] = false;
        $result['errors'][] = 'A transação não tem lançamentos.';
    }

    //testa se o total de débitos é igual ao total de créditos por natureza de contas
    $debitosPatrimonial = 0.0;
    $creditosPatrimonial = 0.0;
    $debitosOrcamentario = 0.0;
    $creditosOrcamentario = 0.0;
    $debitosControle = 0.0;
    $creditosControle = 0.0;
    foreach ($lancamentos as $item) {
        switch ($item['contaContabil'][0]) {
            case 1:
            case 2:
            case 3:
            case 4:
                switch ($item['movimento']) {
                    case 'debito':
                        $debitosPatrimonial += $item['valor'];
                        break;
                    case 'credito':
                        $creditosPatrimonial += $item['valor'];
                        break;
                }
                break;
            case 5:
            case 6:
                switch ($item['movimento']) {
                    case 'debito':
                        $debitosOrcamentario += $item['valor'];
                        break;
                    case 'credito':
                        $creditosOrcamentario += $item['valor'];
                        break;
                }
                break;
            case 7:
            case 8:
                switch ($item['movimento']) {
                    case 'debito':
                        $debitosControle += $item['valor'];
                        break;
                    case 'credito':
                        $creditosControle += $item['valor'];
                        break;
                }
                break;
        }
    }
    if ($debitosPatrimonial !== $creditosPatrimonial) {
        $debitosPatrimonial = formatarMoeda($debitosPatrimonial);
        $creditosPatrimonial = formatarMoeda($creditosPatrimonial);
        $result['success'] = false;
        $result['errors'][] = "O total de débitos nas contas patrimoniais [$debitosPatrimonial] é diferente do total de créditos nas contas dessa natureza [$creditosPatrimonial].";
    }
    if ($debitosOrcamentario !== $creditosOrcamentario) {
        $debitosOrcamentario = formatarMoeda($debitosOrcamentario);
        $creditosOrcamentario = formatarMoeda($creditosOrcamentario);
        $result['success'] = false;
        $result['errors'][] = "O total de débitos nas contas orçamentárias [$debitosOrcamentario] é diferente do total de créditos nas contas dessa natureza [$creditosOrcamentario].";
    }
    if ($debitosControle !== $creditosControle) {
        $debitosControle = formatarMoeda($debitosControle);
        $creditosControle = formatarMoeda($creditosControle);
        $result['success'] = false;
        $result['errors'][] = "O total de débitos nas contas de controle [$debitosControle] é diferente do total de créditos nas contas dessa natureza [$creditosControle].";
    }

    //testa se as contas contábeis informadas existem
    foreach ($lancamentos as $item) {
        if (buscarDadosDaContaContabil($item['contaContabil']) === []) {
            $result['success'] = false;
            $result['errors'][] = "Conta Contábil não encontrada [{$item['contaContabil']}]";
        }
    }

    //testa se os movimentos são apenas débito/crédito
    foreach ($lancamentos as $item) {
        switch ($item['movimento']) {
            case 'debito':
            case 'credito':
                break;
            default:
                $contaContabil = formatarCodigoContaContabil($item['contaContabil']);
                $valor = formatarMoeda($item['valor']);
                $result['success'] = false;
                $result['errors'][] = "O lançamento na conta $contaContabil, no valor $valor não tem movimento de débito/crédito.";
                break;
        }
    }

    //tudo certo até aqui. podemos salvar
    if ($result['success'] === true) {
        $sql = [];
        $sql['INSERT INTO transacoes (id, data, historico) VALUES (:id, :data, :historico);'][] = [
            ':id' => $id,
            ':data' => $data->format('Y-m-d'),
            ':historico' => $historico
        ];
        foreach ($lancamentos as $item) {
            $sql['INSERT INTO lancamentos (transacao, contaContabil, movimento, valor) VALUES (:transacao, :contaContabil, :movimento, :valor);'][] = [
                ':transacao' => $id,
                ':contaContabil' => $item['contaContabil'],
                ':movimento' => $item['movimento'],
                ':valor' => $item['valor']
            ];
        }
        // print_r($sql);
        $result['success'] = salvarNoDb($sql);
        $result['messages'][] = "Transação [$id] foi salva.";
    }

    return $result;
}

function pegarHierarquiaSuperiorDaContaContabil(string $codigo): array
{
    $parte1 = substr($codigo, 0, 3);
    $parte2 = substr($codigo, 3);
    $niveis1 = str_split($parte1, 1);
    $niveis2 = str_split($parte2, 2);
    $niveis = array_merge($niveis1, $niveis2);
    $anterior = '';
    $hierarquia = [];
    foreach ($niveis as $item) {
        $anterior .= $item;
        $hierarquia[] = str_pad($anterior, 9, '0', STR_PAD_RIGHT);
        if ($codigo === str_pad($anterior, 9, '0', STR_PAD_RIGHT)) break;
    }

    return $hierarquia;
}

function atualizarContaContabil(string $codigo, string $nome, string $descricao, string $debitaQuando, string $creditaQuando, string $naturezaSaldo): array
{
    $result = ['success' => true];
    switch ($naturezaSaldo) {
        case 'D':
        case 'C':
        case 'DC':
            break;

        default:
            $result['success'] = false;
            $result['errors'][] = "Natureza do saldo inválida: $naturezaSaldo";
            return $result;
            break;
    }
    $sql['UPDATE planodecontas SET nome = :nome, descricao = :descricao, debitaQuando = :debitaQuando, creditaQuando = :creditaQuando, naturezaSaldo = :naturezaSaldo WHERE codigo = :codigo'][] = [
        ':codigo' => $codigo,
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':debitaQuando' => $debitaQuando,
        ':creditaQuando' => $creditaQuando,
        ':naturezaSaldo' => $naturezaSaldo
    ];

    $result['success'] = salvarNoDb($sql);
    $result['messages'][] = "Conta $codigo atualizada com sucesso.";
    return $result;
}

function excluirContaContabil(string $codigo): array
{
    $result = ['success' => true];
    $info = buscarDadosDaContaContabil($codigo);

    if ($info['tipoNivel'] === 'S') { //verifica se tem contas filhas
        $filhas = buscarContasContabeisFilhas($codigo);
        if (sizeof($filhas) > 0) {
            $result['success'] = false;
            $result['errors'][] = "A conta $codigo é sintética e possui contas contábeis filhas.";
            return $result;
        }
    } elseif ($info['tipoNivel'] === 'A') { //verifica se tem lançamentos
        /*$sql = 'SELECT * FROM lancamentos WHERE contaContabil LIKE :codigo';
        $lancamentos = consultarNoDb($sql, [':codigo' => $codigo]);
        if (sizeof($lancamentos->fetchAll(PDO::FETCH_ASSOC)) > 0) {
            $result['success'] = false;
            $result['errors'][] = "A conta $codigo é analítica e possui lançamentos.";
            return $result;
        }*/
        $con = conexao();
        $stmt = $con->prepare('SELECT * FROM lancamentos WHERE contaContabil LIKE :codigo');
        $stmt->execute([':codigo' => $codigo]);
        if (sizeof($stmt->fetchAll(PDO::FETCH_ASSOC)) > 0) {
            $result['success'] = false;
            $result['errors'][] = "A conta $codigo possui lançamentos.";
            return $result;
        }
    }
    $sql = [];
    $sql['DELETE FROM planodecontas WHERE codigo LIKE :codigo'] = [[':codigo' => $codigo]];
    $exclusao = salvarNoDb($sql);
    if ($exclusao === false) {
        $result['success'] = false;
        $result['errors'][] = "A conta $codigo não foi excluída.";
        return $result;
    }
    $result['messages'][] = "Conta contábil $codigo foi excluída.";
    return $result;
}

function buscarContasContabeisFilhas(string $codigo): array
{
    $parte1 = substr($codigo, 0, 3);
    $parte2 = substr($codigo, 3);
    $niveis1 = str_split($parte1, 1);
    $niveis2 = str_split($parte2, 2);
    $niveis = array_merge($niveis1, $niveis2);

    $buscar = '';
    foreach ($niveis as $item) {
        if ($item === '0') break;
        if ($item === '00') break;
        $buscar .= $item;
    }
    $buscar .= '%';

    /*$sql = 'SELECT * FROM planodecontas WHERE codigo LIKE :base AND codigo NOT LIKE :codigo ORDER BY codigo ASC';
    $filhas = consultarNoDb($sql, [
        ':codigo' => $codigo,
        ':base' => $buscar
    ]);

    return $filhas->fetchAll(PDO::FETCH_ASSOC);*/

    $con = conexao();
    $stmt = $con->prepare('SELECT * FROM planodecontas WHERE codigo LIKE :base AND codigo NOT LIKE :codigo ORDER BY codigo ASC');
    $stmt->execute([
        ':codigo' => $codigo,
        ':base' => $buscar
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarContasContabeisPossiveis(string $codigo): array
{
    $parte1 = substr($codigo, 0, 3);
    $parte2 = substr($codigo, 3);
    $niveis1 = str_split($parte1, 1);
    $niveis2 = str_split($parte2, 2);
    $niveis = array_merge($niveis1, $niveis2);

    $base = '';
    $nivel = 1;
    foreach ($niveis as $item) {
        if ($item === '0') break;
        if ($item === '00') break;
        $base .= $item;
        $nivel++;
    }
    $irmas = [];
    if ($nivel <= 3) {
        $maximo = 9;
        $tamanho = 1;
    } else {
        $maximo = 99;
        $tamanho = 2;
    }
    for ($contador = 1; $contador <= $maximo; $contador++) {
        $irmas[] = str_pad($base . str_pad($contador, $tamanho, '0', STR_PAD_LEFT), 9, '0', STR_PAD_RIGHT);
    }
    return $irmas;
}

function adicionarContaContabil(string $codigo, string $tipoNivel, string $nome, string $descricao, string $debitaQuando, string $creditaQuando, string $naturezaSaldo): array
{
    $result = ['success' => true];
    if (strlen($codigo) < 9) $codigo = str_pad($codigo, 9, '0', STR_PAD_RIGHT); //completa com zeros se o código não tiver 9 caracteres.
    switch ($naturezaSaldo) {
        case 'D':
        case 'C':
        case 'DC':
            break;

        default:
            $result['success'] = false;
            $result['errors'][] = "Natureza do saldo inválida: $naturezaSaldo";
            return $result;
            break;
    }
    $nivel = qualNivelDaContaContabil($codigo);
    // echo $nivel, PHP_EOL;
    if ($nivel <= 3 && $tipoNivel !== 'S') {
        $result['success'] = false;
        $result['errors'][] = "A conta $codigo tem nível $nivel mas não é Sintética: $tipoNivel";
        return $result;
    }
    $sql['INSERT INTO planodecontas (codigo, tipoNivel, nome, descricao, debitaQuando, creditaQuando, naturezaSaldo) VALUES(:codigo, :tipoNivel, :nome, :descricao, :debitaQuando, :creditaQuando, :naturezaSaldo);'][] = [
        ':codigo' => $codigo,
        ':tipoNivel' => $tipoNivel,
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':debitaQuando' => $debitaQuando,
        ':creditaQuando' => $creditaQuando,
        ':naturezaSaldo' => $naturezaSaldo
    ];

    $result['success'] = salvarNoDb($sql);
    $result['messages'][] = "Conta $codigo criada com sucesso.";
    return $result;
}

function qualNivelDaContaContabil(string $codigo): int
{
    $parte1 = substr($codigo, 0, 3);
    $parte2 = substr($codigo, 3);
    $niveis1 = str_split($parte1, 1);
    $niveis2 = str_split($parte2, 2);
    $niveis = array_merge($niveis1, $niveis2);
    $nivel = 0;
    foreach ($niveis as $item) {
        if ($item === '0') break;
        if ($item === '00') break;
        $nivel++;
    }
    return $nivel;
}
