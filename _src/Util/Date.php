<?php

namespace Kontas\Util;

use DateInterval;

/**
 *
 * @author Everton
 */
class Date {
    
    public static function format(string $date): string {
        $obj = date_create_from_format('Y-m-d', $date);
        return $obj->format('d/m/Y');
    }
    
    public static function ultimoDiaDoMes(string $periodo): string {
        $obj = date_create_from_format('Y-m', $periodo);
        $ano = $obj->format('Y');
        $mes = $obj->format('m');
        $dia = $obj->format('t');
        return "$ano-$mes-$dia";
    }
    
    public static function proximoVencimento(string $date): string {
        $obj = date_create_from_format('Y-m-d', $date);
        $obj->add(new DateInterval('P1M'));
        return $obj->format('Y-m-d');
    }
    
    public static function parseInput(string $input): string {
        $obj = date_create_from_format('dmY', $input);
        return $obj->format('Y-m-d');
    }
}
