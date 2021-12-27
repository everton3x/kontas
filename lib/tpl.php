<?php

/**
 * Funções para templating
 */

function carregaTemplate(string $template, array $data = []): void
{
    $tplFile = "../tpl/$template.php";
    if(sizeof($data) > 0){
        extract($data);
    }
    require $tplFile;
}
