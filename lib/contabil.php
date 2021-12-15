<?php

/**
 * Funções relacionadas à contabilidade
 */

function buscarContasContabeis(): PDOStatement
{
    return consultarNoDb('SELECT * FROM planodecontas ORDER BY codigo ASC');
}

function gerarCodigoDeTransacao(): string
{
    return sha1(microtime() . rand());
}

function buscarDadosDaContaContabil(string $codigo): array
{
    $stmt = consultarNoDb('SELECT * FROM planodecontas WHERE codigo = :codigo', [':codigo' => $codigo]);
    if ($stmt === false) return [];
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
