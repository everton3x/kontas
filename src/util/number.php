<?php

namespace kontas\util;

/**
 * Description of number
 *
 * @author Everton
 */
class number {
    public static function format(float|int $number): string {
        return number_format($number, 2, ',', '.');
    }
}
