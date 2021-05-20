<?php

namespace kontas\util;

/**
 * Description of number
 *
 * @author Everton
 */
class number {
    public static function format(float|int $number): string {
        
        if($number < 0){
            $num = number_format($number*-1, 2, ',', '.');
            return "<red>($num)</red>";
        }
        
        $num = number_format($number, 2, ',', '.');
        return "<green>$num</green>";
    }
}
