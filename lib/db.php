<?php

/**
 * Funções de banco de dados
 */

function conexao(): PDO
{
    $dsn = "mysql:host=localhost;dbname=kontasdb";
    $user = 'root';
    $passowrd = '';
    $con = new PDO($dsn, $user, $passowrd);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $con;
}

/*function consultarNoDb(string $sql, array $data = []): PDOStatement|bool
{
    $con = conexao();
    if (strtolower(substr($sql, 0, 6)) !== 'select') {
        trigger_error("Consulta [$sql] não é do tipo SELECT.", E_USER_ERROR);
    }
    if ($data !== []) {
        $sth = $con->prepare($sql);
        $sth->execute($data);
    } else {
        $sth = $con->query($sql);
    }
    return $sth;
}*/

function salvarNoDb(array $sql): bool
{
    // print_r($sql);
    $con = conexao();
    $con->beginTransaction();
    try {
        foreach ($sql as $stmt => $data) {
            // print_r($stmt);
            switch (strtolower(substr($stmt, 0, 6))) {
                case 'insert':
                case 'update':
                case 'delete':
                    break;
                default:
                    trigger_error("Consulta [$stmt] não é do tipo INSERT|UPDATE|DELETE.", E_USER_ERROR);
            }
            $prepared = $con->prepare($stmt);
            // print_r($data);
            foreach($data as $item){
                $prepared->execute($item);
            }
        }
        $con->commit();
    } catch (PDOException $ex) {
        $con->rollBack();
        trigger_error($ex->getTraceAsString(), E_USER_ERROR);
    }
    return true;
}
