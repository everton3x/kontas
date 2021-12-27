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

function gerarId(): string
{
    return (string) sha1(microtime().random_int(0,999));
}