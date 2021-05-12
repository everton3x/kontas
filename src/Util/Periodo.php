<?php

namespace Kontas\Util;

/**
 *
 * @author Everton
 */
class Periodo {
    
    public static function format(string $periodo): string {
        $obj = date_create_from_format('Y-m', $period);
        return $obj->format('m/Y');
    }
    
    public static function parseInput(string $input): string {
        $obj = date_create_from_format('mY', $input);
        return $obj->format('Y-m');
    }
    
    public static function anterior(string $periodo): string {
        $date = date_create_from_format('Y-m', $periodo);
        $date->sub(new DateInterval('P1M'));
        return $date->format('Y-m');
    }
    
    public static function posterior(string $periodo): string {
        $date = date_create_from_format('Y-m', $periodo);
        $date->add(new DateInterval('P1M'));
        return $date->format('Y-m');
    }
    
    public static function testar(string $periodo): void {
        if(mb_strlen($periodo) !== 7){
            throw new Exception("Tamanho do período [$periodo] é inválido: ". mb_strlen($periodo));
        }
        
        try{
            $date = date_create_from_format('Y-m', $periodo);
        } catch (Exception $ex) {
            throw new Exception("Período [$periodo] não origina uma data válida.");
        }
    }
}
