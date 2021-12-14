<?php

/**
 * Funções para templating
 */

function carregaTemplate(string $template, array $data = []): void
{
    $tplFile = "../tpl/$template.php";
    if(!file_exists($tplFile)){
        trigger_error("Arquivo $tplFile não encontrado!", E_USER_ERROR);
    }
    if(sizeof($data) > 0){
        extract($data);
    }
    include $tplFile;
}