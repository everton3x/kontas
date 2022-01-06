<?php
/**
 * Funções para a despesa
 */

function salvarDespesa($periodo, $descricao, $valorInicial, $agrupador, $parcela, $tags, $gastoem, $mp, $autopagar): array
{
    $result['success'] = null;
    $result['messages'] = [];
    $result['errors'] = [];

    if(!testarPeriodo($periodo)){
        $result['success'] = false;
        $result['errors'][] = "O período não é válido: $periodo";
    }
    if(strlen($descricao) === 0){
        $result['success'] = false;
        $result['errors'][] = "A descrição está vazia.";
    }
    $valorInicial = round($valorInicial, 2);
    if($valorInicial <= 0){
        $result['success'] = false;
        $result['errors'][] = "O valor inicial é menor ou igual a zero: $valorInicial";
    }

    // print_r($result);
    if($result['success'] === false) return $result;

    $con = conexao();

    try{
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

        /*if(!is_null($gastoem)){
            $recebidoem = date_create_from_format('Y-m-d', $recebidoem);
            $recebimento = salvarRecebimento($cod, $valorInicial, $recebidoem->format('Y-m-d'), "Recebimento automático.");
            if($recebimento['success'] === false){
                $result['success'] = false;
                $result['errors'] = array_merge($result['errors'], $recebimento['errors']);
            }else{
                $result['messages'] = array_merge($result['messages'], $recebimento['messages']);
            }
        }*/

        if(sizeof($tags) > 0){
            $stmt = $con->prepare('INSERT INTO tags (tag, despesa) VALUES(:tag, :despesa)');
            foreach($tags as $tag){
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
    }catch(Exception $e){
        $con->rollBack();
        $result['success'] = false;
        $result['errors'][] = $stmt->errorInfo()[2];
    }finally{
        // print_r($result);
        return $result;
    }
}

function buscarDadosDaDespesa(string $cod): array
{
    $con = conexao();
    // $stmt = $con->prepare('SELECT * FROM receitasresumo WHERE cod = :cod');
    $stmt = $con->prepare('SELECT * FROM despesasresumo WHERE cod = :cod');
    if($stmt->execute([':cod' => $cod]) === false) return [];
    $detalhes = $stmt->fetch(PDO::FETCH_ASSOC);
    if($detalhes === false) return  [];
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
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $item){
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

    try{
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
    }catch(Exception $e){
        $con->rollBack();
        $result['success'] = false;
        $result['errors'][] = $stmt->errorInfo()[2];
    }finally{
        // print_r($result);
        return $result;
    }
}