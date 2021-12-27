<?php

/**
 * Funções de tempo
 */

 /**
  * @param string $periodo AAAA-MM
  */
function periodo2DateTime(string $periodo): DateTime
{
    $datetime = date_create_from_format('Y-m', $periodo);
    $datetime->setDate(
        $datetime->format('Y'),//ano
        $datetime->format('m'),//mês
        $datetime->format('t')//último dia do mês
    );
    return $datetime;
}

/**
 * @param string|DateTime $periodo string = AAAA-MM
 */
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

/**
 * @param string|DateTime $periodo string = AAAA-MM
 */
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

/**
  * @param string $periodo AAAA-MM
  */
  function periodo2Int(string $periodo): int
  {
      $dt = periodo2DateTime($periodo);
      return (int) $dt->format('Ym');
  }

  /**
   * Testa se o período é válido.
   * 
   * @param string $periodo AAAA-MM
   */
  function testarPeriodo(string $periodo): bool
  {
    return checkdate(substr($periodo, -1, 2), 1, substr($periodo, 0, 4));
  }

  function dateTime2Periodo(DateTime $dt): string
  {
      return $dt->format('Y-m');
  }

  function int2DateTime(int $number): DateTime
  {
       return periodo2DateTime(substr($number, 0, 4).'-'.substr($number, -1, 2));
  }