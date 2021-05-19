<?php

namespace kontas\util;

/**
 * Description of date
 *
 * @author Everton
 */
class date {
    /**
     * 
     * @param string $date aaaa-mm-dd
     * @return string dd/mm/aaaa
     */
    public static function format(string $date): string {
        $dt = \DateTime::createFromFormat('Y-m-d', $date);
        return $dt->format('d/m/Y');
    }
    
    /**
     * 
     * @param string $input ddmmaaaa
     * @return string aaaa-mm-dd
     */
    public static function parseInput(string $input): string {
        $dt = \DateTime::createFromFormat('dmY', $input);
        return $dt->format('Y-m-d');
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return string aaaa-mm-dd
     */
    public static function lastDayOfMonth(string $periodo): string {
        $dt = \DateTime::createFromFormat('Y-m', $periodo);
        return $dt->format('Y-m-t');
    }
    
}
