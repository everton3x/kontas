<?php

namespace Kontas\Util;

/**
 *
 * @author Everton
 */
class Date {
    public static function format(string $date): string {
        $obj = date_create_from_format('dmY', $date);
        return $obj->format('d/m/Y');
    }
    
    public static function ultimoDiaDoMes(string $periodo): string {
        $obj = date_create_from_format('Ym', $periodo);
        $ano = $obj->format('Y');
        $mes = $obj->format('m');
        $dia = $obj->format('t');
        return "$dia$mes$ano";
    }
    
    public static function proximoVencimento(string $date): string {
        $obj = date_create_from_format('dmY', $date);
        $obj->add(new \DateInterval('P1M'));
        return $obj->format('dmY');
    }
}
