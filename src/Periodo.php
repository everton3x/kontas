<?php

namespace Kontas;

use DateInterval;

/**
 * Description of Periodo
 *
 * @author Everton
 */
class Periodo {
        
    public static function periodoExiste(string $periodo): bool {
        return file_exists(Config::DATA_DIR.$periodo.'.json');
    }
    
    public static function periodoAnterior(string $periodo): string {
        $atual = date_create_from_format('Ym', $periodo);
        $intervalo = new DateInterval('P1M');
        $anterior = date_sub($atual, $intervalo);
        return $anterior->format('Ym');
    }
    
    public static function formataParaCompetencia(string $periodo): string {
        $atual = date_create_from_format('Ym', $periodo);
        return $atual->format('m/Y');
    }
}
