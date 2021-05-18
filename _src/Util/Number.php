<?php

namespace Kontas\Util;

/**
 *
 * @author Everton
 */
class Number {
    public static function format($number): string {
        $formatado = '<green>'.number_format($number, 2, ',', '.').'</green>';
        
        if($number == 0){
            $formatado = '-';
        }
        
        if($number < 0){
            $formatado = '<red>('.number_format($number*-1, 2, ',', '.').')</red>';
        }
        
        return $formatado;
    }
}
