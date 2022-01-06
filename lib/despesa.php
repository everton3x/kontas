<?php

/**
 * Funções para a despesa
 */

function salvarDespesa(string $periodo, string $descricao, float $valorInicial, string $agrupador, int $parcela, array $tags, ?string $gastoem, ?int $mp, ?int $autopagar): array
{
    $result['success'] = null;
    $result['messages'] = [];
    $result['errors'] = [];

    if (!testarPeriodo($periodo)) {
        $result['success'] = false;
        $result['errors'][] = "O período não é válido: $periodo";
    }
    if (strlen($descricao) === 0) {
        $result['success'] = false;
        $result['errors'][] = "A descrição está vazia.";
    }
    $valorInicial = round($valorInicial, 2);
    if ($valorInicial <= 0) {
        $result['success'] = false;
        $result['errors'][] = "O valor inicial é menor ou igual a zero: $valorInicial";
    }

    // print_r($result);
    if ($result['success'] === false) return $result;

    $con = conexao();

    try {
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO despesas (periodo, descricao, valorInicial, agrupador, parcela) VALUES (:periodo, :descricao, :valorInicial, :agrupador, :parcela)');
        $stmt->execute([
            ':periodo' => periodo2Int($periodo),
            ':descricao' => $descricao,
            ':valorInicial' => $valorInicial,
            ':agrupador' => $agrupador,
            ':parcela' => $parcela
        ]);
        $cod = $con->lastInsertId();

        if (!is_null($gastoem)) {
            $gastoem = date_create_from_format('Y-m-d', $gastoem);
            $gasto = salvarGasto($cod, $gastoem->format('Y-m-d'), $valorInicial, null, "Gasto automático.", $mp, $autopagar);
            if ($gasto['success'] === false) {
                $result['success'] = false;
                $result['errors'] = array_merge($result['errors'], $gasto['errors']);
            } else {
                $result['messages'] = array_merge($result['messages'], $gasto['messages']);
            }
        }

        if (sizeof($tags) > 0) {
            $stmt = $con->prepare('INSERT INTO tags (tag, despesa) VALUES(:tag, :despesa)');
            foreach ($tags as $tag) {
                $stmt->execute([
                    ':tag' => $tag,
                    ':despesa' => $cod
                ]);
            }
        }

        $con->commit();
        $result['success'] = true;
        $result['messages'][] = "Despesa salva com o código: $cod";
        $result['cod'] = $cod;
    } catch (Exception $e) {
        $con->rollBack();
        $result['success'] = false;
        $result['errors'][] = $stmt->errorInfo()[2];
    } finally {
        // print_r($result);
        return $result;
    }
}

function buscarDadosDaDespesa(string $cod): array
{
    $con = conexao();
    // $stmt = $con->prepare('SELECT * FROM receitasresumo WHERE cod = :cod');
    $stmt = $con->prepare('SELECT * FROM despesasresumo WHERE cod = :cod');
    if ($stmt->execute([':cod' => $cod]) === false) return [];
    $detalhes = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($detalhes === false) return  [];
    $detalhes['tags'] = buscarTagsDaDespesa($cod);
    // print_r($detalhes);
    //linhas necessárias porque no autorecebimento, como está dentro da transação, ainda não tem a receita efetivamente no banco buscar
    // if(!key_exists('valorInicial', $detalhes)) $detalhes['valorInicial'] = 0.0;
    // if(!key_exists('alteracao', $detalhes)) $detalhes['alteracao'] = 0.0;
    // if(!key_exists('recebido', $detalhes)) $detalhes['recebido'] = 0.0;

    $detalhes['previsto'] = round($detalhes['valorInicial'] + $detalhes['alteracao'], 2);
    $detalhes['agastar'] = round($detalhes['previsto'] - $detalhes['gasto'], 2);
    $detalhes['apagar'] = round($detalhes['gasto'] - $detalhes['pago'], 2);

    /*$stmt = $con->prepare('SELECT * FROM receitaalteracao WHERE receita = :cod');
    if($stmt->execute([':cod' => $cod]) === false) $detalhes['alteracoes'] = [];
    $detalhes['alteracoes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $con->prepare('SELECT * FROM recebimentos WHERE receita = :cod');
    if($stmt->execute([':cod' => $cod]) === false) $detalhes['recebimentos'] = [];
    $detalhes['recebimentos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);*/

    return $detalhes;
}

function buscarTagsDaDespesa(string $cod): array
{
    $con = conexao();
    $stmt = $con->prepare('SELECT tag FROM tags WHERE despesa LIKE :despesa');
    $stmt->execute([':despesa' => $cod]);
    $tags = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $item) {
        $tags[] = $item['tag'];
    }
    return $tags;
}

function salvarAlteracaoDespesa(string $despesa, float $valor, string $observacao): array
{
    $result['success'] = null;
    $result['messages'] = [];
    $result['errors'] = [];

    $valor = round($valor, 2);

    $con = conexao();

    try {
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO despesaalteracao (despesa, valor, observacao) VALUES (:despesa, :valor, :observacao)');
        $stmt->execute([
            ':despesa' => $despesa,
            ':valor' => $valor,
            ':observacao' => $observacao
        ]);

        $con->commit();
        $result['success'] = true;
        $result['messages'][] = "Alteração salva!";
    } catch (Exception $e) {
        $con->rollBack();
        $result['success'] = false;
        $result['errors'][] = $stmt->errorInfo()[2];
    } finally {
        // print_r($result);
        return $result;
    }
}

function salvarGasto(int $despesa, string $data, float $valor, ?string $vencimento = null, ?string $observacao = null, int $mp, int $autopagar): array
{
    $result['success'] = null;
    $result['messages'] = [];
    $result['errors'] = [];

    $valor = round($valor, 2);

    $con = conexao();

    if ($autopagar === 0) {
        $stmt = $con->query("SELECT autopagar FROM mp WHERE cod = $mp");
        if ($stmt->fetch(PDO::FETCH_ASSOC)['autopagar'] === 1) $autopagar = 1;
    }

    try {
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO gastos (despesa, data, valor, mp, vencimento, observacao) VALUES (:despesa, :data, :valor, :mp, :vencimento, :observacao)');
        $stmt->execute([
            ':despesa' => $despesa,
            ':data' => $data,
            ':valor' => $valor,
            ':mp' => $mp,
            ':vencimento' => $vencimento,
            ':observacao' => $observacao
        ]);
        $cod = $con->lastInsertId();

        if ($autopagar === 1) {
            $pagamento = salvarPagamento($cod, $data, $valor, 'Pagamento automático.');
            if ($pagamento['success'] === false) {
                $result['success'] = false;
                $result['errors'] = array_merge($result['errors'], $pagamento['errors']);
            } else {
                $result['messages'] = array_merge($result['messages'], $pagamento['messages']);
            }
        }
        $con->commit();
        $result['success'] = true;
        $result['messages'][] = "Gasto salvo com código $cod.";
        $result['cod'] = $cod;
    } catch (Exception $e) {
        $con->rollBack();
        $result['success'] = false;
        $result['errors'][] = $stmt->errorInfo()[2];
    } finally {
        // print_r($result);
        return $result;
    }
}

function salvarPagamento(int $gasto, string $data, float $valor, ?string $observacao = null): array
{
    $result['success'] = null;
    $result['messages'] = [];
    $result['errors'] = [];

    $valor = round($valor, 2);

    $con = conexao();

    try {
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO pagamentos (gasto, data, valor, observacao) VALUES (:gasto, :data, :valor, :observacao)');
        $stmt->execute([
            ':gasto' => $gasto,
            ':data' => $data,
            ':valor' => $valor,
            ':observacao' => $observacao
        ]);
        $cod = $con->lastInsertId();

        $con->commit();
        $result['success'] = true;
        $result['messages'][] = "Pagamento salvo com código $cod.";
        $result['cod'] = $cod;
    } catch (Exception $e) {
        $con->rollBack();
        $result['success'] = false;
        $result['errors'][] = $stmt->errorInfo()[2];
    } finally {
        // print_r($result);
        return $result;
    }
}
