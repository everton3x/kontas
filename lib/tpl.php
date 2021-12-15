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

function montaOptionsDasContasContabeis(): string
{
    $contasContabeis = buscarContasContabeis();
    if($contasContabeis === false) return '';
    if($contasContabeis->rowCount() === 0) return '';
    $options = '';
    $anterior = '';
    foreach($contasContabeis->fetchAll(PDO::FETCH_ASSOC) as $item){
        if($item['status'] != 0) continue;
        $codigoFormatado = formatarCodigoContaContabil($item['codigo']);
        if($item['tipoNivel'] == 'S' && $anterior !== 'A'){
            $options .= "<optgroup label='{$codigoFormatado} {$item['nome']}'>".PHP_EOL;
            $anterior = $item['tipoNivel'];
        }elseif ($item['tipoNivel'] == 'S' && $anterior === 'A') {
            $options .= '</optgroup>'.PHP_EOL;
            $group = false;
        }else{
            $options .= "<option value='{$item['codigo']}'>{$codigoFormatado} {$item['nome']}</option>".PHP_EOL;
        }
    }
    return $options;
}

function formatarCodigoContaContabil(string $codigo): string
{
    return $codigo[0].'.'.$codigo[1].'.'.$codigo[2].'.'.$codigo[3].$codigo[4].'.'.$codigo[5].$codigo[6].'.'.$codigo[7].$codigo[8];
}

function formatarMoeda(int|float $valor): string
{
    return number_format($valor, 2, ',', '.');
}