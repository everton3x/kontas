<?php

/**
 * Funções de tempo
 */

 /**
  * @param string $periodo AAAAMM
  */
function periodo2DateTime(string $periodo): DateTime
{
    $datetime = date_create_from_format('Ym', $periodo);
    $datetime->setDate(
        $datetime->format('Y'),//ano
        $datetime->format('m'),//mês
        $datetime->format('t')//último dia do mês
    );
    return $datetime;
}

function proximoPeriodo(string|DateTime $periodo): DateTime
{
    if($periodo instanceof DateTime){
        $datetime = clone $periodo;
    }else{
        $datetime = periodo2DateTime($periodo);
    }
    // $ultimoDia = $datetime->format('t');
    $ultimoDia = 28;
    $interval = new DateInterval("P{$ultimoDia}D");
    $datetime->add($interval);
    $datetime->setDate(
        $datetime->format('Y'),
        $datetime->format('m'),
        $datetime->format('t')
    );
    return $datetime;
}

function periodoAnterior(string|DateTime $periodo): DateTime
{
    if($periodo instanceof DateTime){
        $datetime = clone $periodo;
    }else{
        $datetime = periodo2DateTime($periodo);
    }
    $ultimoDia = $datetime->format('t');
    $interval = new DateInterval("P{$ultimoDia}D");
    $datetime->sub($interval);
    $datetime->setDate(
        $datetime->format('Y'),
        $datetime->format('m'),
        $datetime->format('t')
    );
    return $datetime;
}