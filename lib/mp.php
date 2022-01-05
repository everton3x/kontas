<?php

/**
 * Funções de meio de pagamento
 */
function salvarMeioPagamento(string $mp, bool $autopagar): array
{
    $result['success'] = null;
    $result['messages'] = [];
    $result['errors'] = [];

    if (strlen($mp) === 0) {
        $result['success'] = false;
        $result['errors'][] = "O nome do meio de pagamento está vazio.";
    }
    if ($result['success'] === false) return $result;

    $con = conexao();

    try {
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO mp (mp, autopagar) VALUES (:mp, :autopagar)');
        $stmt->execute([
            ':mp' => $mp,
            ':autopagar' => (int) $autopagar
        ]);
        $cod = $con->lastInsertId();

        $con->commit();
        $result['success'] = true;
        $result['messages'][] = "Meio de pagamento salvo com o código: $cod";
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

function listarMeiosPagamento(int $status = 0): array
{
    $con = conexao();
    $stmt = $con->prepare('SELECT * FROM mp WHERE status = :status ORDER BY mp ASC');
    $stmt->execute([':status' => $status]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function detalhesMeiosPagamento(int $cod): array
{
    $con = conexao();
    $stmt = $con->prepare('SELECT * FROM mp WHERE cod = :cod');
    $stmt->execute([':cod' => $cod]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function salvarAlteracaoMeioPagamento(int $cod, string $mp, int $autopagar, int $status): array
{
    $result['success'] = null;
    $result['messages'] = [];
    $result['errors'] = [];

    if (strlen($mp) === 0) {
        $result['success'] = false;
        $result['errors'][] = "O nome do meio de pagamento está vazio.";
    }
    if ($result['success'] === false) return $result;

    $con = conexao();

    try {
        $con->beginTransaction();
        $stmt = $con->prepare('UPDATE mp SET mp = :mp, autopagar = :autopagar, status = :status WHERE cod = :cod');
        $stmt->execute([
            ':cod' => $cod,
            ':mp' => $mp,
            ':autopagar' => (int) $autopagar,
            ':status' => $status
        ]);

        $con->commit();
        $result['success'] = true;
        $result['messages'][] = "Meio de pagamento autalizado no código: $cod";
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
