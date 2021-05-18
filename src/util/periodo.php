<?php

namespace kontas\util;

/**
 * Description of periodo
 *
 * @author Everton
 */
class periodo {
    
    /**
     * 
     * @param string $input mmaaaa
     * @return string aaaa-mm
     */
    public static function parseInput(string $input): string {
        
        if(preg_match('/[[:digit:]]{6}/', $input) !== 1){
            trigger_error("Valor inválido para o formato mmaaaa: $input", E_USER_ERROR);
        }
        
        $dt = \DateTime::createFromFormat('mY', $input);

        return $dt->format('Y-m');
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return string mm/aaaa
     */
    public static function format(string $periodo): string {
        $dt = new \DateTime();
        $dt->createFromFormat('Y-m', $periodo);
        return $dt->format('m/Y');
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return bool
     */
    public static function periodoExists(string $periodo): bool {
        $filename = \kontas\config::PERIODOS_DIR.$periodo.'.json';
        return file_exists($filename);
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return string aaaa-mm
     */
    public static function periodoAnterior(string $periodo): string {
        $dt = \DateTime::createFromFormat('Y-m', $periodo);
        $dti = new \DateInterval('P1M');
        $dt->sub($dti);
        return $dt->format('Y-m');
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return string aaaa-mm
     */
    public static function periodoPosterior(string $periodo): string {
        $dt = \DateTime()::createFromFormat('Y-m', $periodo);
        $dti = new \DateInterval('P1M');
        $dt->add($dti);
        return $dt->format('Y-m');
    }
}
