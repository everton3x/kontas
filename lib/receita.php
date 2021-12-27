<?php

/**
 * Funções para receita
 */

function salvarReceita(string $periodo, string $descricao, float $valorInicial, string $agrupador, int $parcela, array $tags): array
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

    $cod = gerarId();
    $con = conexao();

    try{
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO receitas (cod, periodo, descricao, valorInicial, agrupador, parcela) VALUES (:cod, :periodo, :descricao, :valorInicial, :agrupador, :parcela)');
        $stmt->execute([
            ':cod' => $cod,
            ':periodo' => periodo2Int($periodo),
            ':descricao' => $descricao,
            ':valorInicial' => $valorInicial,
            ':agrupador' => $agrupador,
            ':parcela' => $parcela
        ]);

        if(sizeof($tags) > 0){
            $stmt = $con->prepare('INSERT INTO tags (tag, receita) VALUES(:tag, :receita)');
            foreach($tags as $tag){
                $stmt->execute([
                    ':tag' => $tag,
                    ':receita' => $cod
                ]);
            }
        }

        $con->commit();
        $result['success'] = true;
        $result['messages'][] = "Receita salva com o código: $cod";
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

function buscarDadosDaReceita(string $cod): array
{
    $con = conexao();
    $stmt = $con->prepare('SELECT * FROM receitas WHERE cod LIKE :cod');
    if($stmt->execute([':cod' => $cod]) === false) return [];
    return $stmt->fetch(PDO::FETCH_ASSOC);
}