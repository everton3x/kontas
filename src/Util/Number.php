<?php

namespace Kontas\Util;

/**
 * Description of Number
 *
 * @author Everton
 */
class Number {
    public static function format($number): string {
        $formatado = number_format($number, 2, ',', '.');
        
        if($number == 0){
            $formatado = '-';
        }
        
        if($number < 0){
            $formatado = '('.number_format($number*-1, 2, ',', '.').')';
        }
        
        return $formatado;
    }
}
