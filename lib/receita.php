<?php

/**
 * Funções para receita
 */

function salvarReceita(string $periodo, string $descricao, float $valorInicial, string $agrupador, int $parcela, array $tags, ?string $recebidoem = null): array
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

    // $cod = gerarId();
    $con = conexao();

    try{
        $con->beginTransaction();
        // $stmt = $con->prepare('INSERT INTO receitas (cod, periodo, descricao, valorInicial, agrupador, parcela) VALUES (:cod, :periodo, :descricao, :valorInicial, :agrupador, :parcela)');
        $stmt = $con->prepare('INSERT INTO receitas (periodo, descricao, valorInicial, agrupador, parcela) VALUES (:periodo, :descricao, :valorInicial, :agrupador, :parcela)');
        $stmt->execute([
            // ':cod' => $cod,
            ':periodo' => periodo2Int($periodo),
            ':descricao' => $descricao,
            ':valorInicial' => $valorInicial,
            ':agrupador' => $agrupador,
            ':parcela' => $parcela
        ]);
        $cod = $con->lastInsertId();

        if(!is_null($recebidoem)){
            $recebidoem = date_create_from_format('Y-m-d', $recebidoem);
            $recebimento = salvarRecebimento($cod, $valorInicial, $recebidoem->format('Y-m-d'), "Recebimento automático.");
            if($recebimento['success'] === false){
                $result['success'] = false;
                $result['errors'] = array_merge($result['errors'], $recebimento['errors']);
            }else{
                $result['messages'] = array_merge($result['messages'], $recebimento['messages']);
            }
        }

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

function atualizarReceita(int $cod, string $periodo, string $descricao, float $valorInicial, string $agrupador, int $parcela, array $tags): array
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

    if($result['success'] === false) return $result;

    $con = conexao();

    try{
        $con->beginTransaction();
        $stmt = $con->prepare('UPDATE receitas SET periodo = :periodo, descricao = :descricao, valorInicial = :valorInicial, agrupador = :agrupador, parcela = :parcela WHERE cod = :cod');
        $stmt->execute([
            ':cod' => $cod,
            ':periodo' => periodo2Int($periodo),
            ':descricao' => $descricao,
            ':valorInicial' => $valorInicial,
            ':agrupador' => $agrupador,
            ':parcela' => $parcela
        ]);
        $cod = $con->lastInsertId();

        $stmt = $con->prepare('DELETE FROM tags WHERE receita = :cod');
        $stmt->execute([
            ':cod' => $cod
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
        $result['messages'][] = "Receita editada com sucesso.";
    }catch(Exception $e){
        $con->rollBack();
        $result['success'] = false;
        $result['errors'][] = $stmt->errorInfo()[2];
    }finally{
        // print_r($result);
        return $result;
    }
}

function salvarAlteracaoReceita(string $receita, float $valor, string $observacao): array
{
    $result['success'] = null;
    $result['messages'] = [];
    $result['errors'] = [];

    $valor = round($valor, 2);
    
    $con = conexao();

    try{
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO receitaalteracao (receita, valor, observacao) VALUES (:receita, :valor, :observacao)');
        $stmt->execute([
            ':receita' => $receita,
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

function salvarRecebimento(string $receita, float $valor, string $data, string $observacao): array
{
    $result['success'] = null;
    $result['messages'] = [];
    $result['errors'] = [];
    $detalhes = buscarDadosDaReceita($receita);
    // print_r($detalhes);
    $suplementar = 0.0;
    //corrige problema de quando a receita e o recebimento estão na mesma transação
    if($detalhes !== [] && $detalhes['areceber'] < $valor) $suplementar = round($valor - $detalhes['areceber'], 2);

    $valor = round($valor, 2);
    $data = date_create_from_format('Y-m-d', $data);
    
    $con = conexao();

    try{
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO recebimentos (receita, valor, data, observacao) VALUES (:receita, :valor, :data, :observacao)');
        $stmt->execute([
            ':receita' => $receita,
            ':valor' => $valor,
            ':data' => $data->format('Y-m-d'),
            ':observacao' => $observacao
        ]);
        if($suplementar > 0.0){
            salvarAlteracaoReceita($receita, $suplementar, "Valor suplementado automaticamente no recebimento: {$con->lastInsertId()}");
            $result['messages'][] = "Suplementação da receita realizada: ".formatNumber($suplementar);
        }

        $con->commit();
        $result['success'] = true;
        $result['messages'][] = "Recebimento salvo!";
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
    $stmt = $con->prepare('SELECT * FROM receitasresumo WHERE cod = :cod');
    if($stmt->execute([':cod' => $cod]) === false) return [];
    $detalhes = $stmt->fetch(PDO::FETCH_ASSOC);
    if($detalhes === false) return  [];
    $detalhes['tags'] = buscarTagsDaReceita($cod);
    // print_r($detalhes);
    //linhas necessárias porque no autorecebimento, como está dentro da transação, ainda não tem a receita efetivamente no banco buscar
    // if(!key_exists('valorInicial', $detalhes)) $detalhes['valorInicial'] = 0.0;
    // if(!key_exists('alteracao', $detalhes)) $detalhes['alteracao'] = 0.0;
    // if(!key_exists('recebido', $detalhes)) $detalhes['recebido'] = 0.0;

    $detalhes['previsto'] = round($detalhes['valorInicial'] + $detalhes['alteracao'], 2);
    $detalhes['areceber'] = round($detalhes['previsto'] - $detalhes['recebido'], 2);

    $stmt = $con->prepare('SELECT * FROM receitaalteracao WHERE receita = :cod');
    if($stmt->execute([':cod' => $cod]) === false) $detalhes['alteracoes'] = [];
    $detalhes['alteracoes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $con->prepare('SELECT * FROM recebimentos WHERE receita = :cod');
    if($stmt->execute([':cod' => $cod]) === false) $detalhes['recebimentos'] = [];
    $detalhes['recebimentos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $detalhes;
}

function buscarTagsDaReceita(string $cod): array
{
    $con = conexao();
    $stmt = $con->prepare('SELECT tag FROM tags WHERE receita LIKE :receita');
    $stmt->execute([':receita' => $cod]);
    $tags = [];
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $item){
        $tags[] = $item['tag'];
    }
    return $tags;
}

function salvarReceitaRepetida(string $periodo, string $descricao, float $valorInicial, string $agrupador, int $parcelas, array $tags): array
{
    $result['success'] = true;
    $result['messages'] = [];
    $result['errors'] = [];
    $ocorreuErro = false;
    for($i = 1; $i <= $parcelas; $i++){
        if($periodo instanceof DateTime) $periodo = $periodo->format('Y-m');
        $return = salvarReceita($periodo, $descricao, $valorInicial, $agrupador, 0, $tags);
        $result['messages'] = array_merge($result['messages'], $return['messages']);
        $result['errors'] = array_merge($result['errors'], $return['errors']);
        if($return['success'] === false) $ocorreuErro = true;
        $periodo = proximoPeriodo($periodo);
    }
    if($ocorreuErro) $result['success'] = false;
    return $result;
}

function salvarReceitaParcelada($periodos, $descricao, $valores, $agrupador, $parcelas, $tags, $valor, $parcela): array
{
    $result['success'] = true;
    $result['messages'] = [];
    $result['errors'] = [];
    $ocorreuErro = false;

    if(strlen($agrupador) == 0) {
        $result['success'] = false;
        $result['errors'][] = 'Agrupador é obrigatório.';
        $ocorreuErro = true;
    }
    $soma = round(array_sum($valores), 2);
    if($valor != $soma) {
        $result['success'] = false;
        $result['errors'][] = "A soma das parcelas $soma é diferente do total: $valor";
        $ocorreuErro = true;
    }
    $count = sizeof($parcelas);
    if($count != $parcela) {
        $result['success'] = false;
        $result['errors'][] = "O total das parcelas $count é diferente das parcelas: $parcela";
        $ocorreuErro = true;
    }
    $count = sizeof($periodos);
    if($count != $parcela) {
        $result['success'] = false;
        $result['errors'][] = "O total de períodos $count é diferente das parcelas: $parcela";
        $ocorreuErro = true;
    }
    $count = sizeof($valores);
    if($count != $parcela) {
        $result['success'] = false;
        $result['errors'][] = "O total de valores $count é diferente das parcelas: $parcela";
        $ocorreuErro = true;
    }
    if($ocorreuErro) return $result;

    for($i = 1; $i <= $parcela; $i++){
        $periodo = $periodos[$i];
        $v = $valores[$i];
        $p = $parcelas[$i];
        if($periodo instanceof DateTime) $periodo = $periodo->format('Y-m');
        $return = salvarReceita($periodo, $descricao, $v, $agrupador, $p, $tags);
        $result['messages'] = array_merge($result['messages'], $return['messages']);
        $result['errors'] = array_merge($result['errors'], $return['errors']);
        if($return['success'] === false) $ocorreuErro = true;
    }
    if($ocorreuErro) $result['success'] = false;
    return $result;
}