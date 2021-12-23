<?php

/**
 * Funções sobre configuração
 */


function carregaConfiguracao(): array
{
    return parse_ini_file('../config/config.ini', true);

}

function buscaConfiguracaoReceita(string $key): mixed
{
    return carregaConfiguracao()['receita'][$key];
}