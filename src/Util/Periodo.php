<?php

namespace Kontas\Util;

use DateInterval;
use Exception;
use Kontas\Config\Config;
use Kontas\Exception\FailException;
use Kontas\Json\Json;
use Kontas\Recordset\PeriodoRecord;

/**
 *
 * @author Everton
 */
class Periodo {
    
    public static function format(string $periodo): string {
        $obj = date_create_from_format('Y-m', $periodo);
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
    
    public static function criar(string $periodo, ?string $copiar = null): PeriodoRecord {
        
        self::testar($periodo);
        
        $anterior = self::anterior($periodo);
        $anteriorJsonFile = Config::periodosJsonDir().$anterior.'.json';
        if(file_exists($anteriorJsonFile) === false){
            $anterior = self::format($anterior);
            $periodo = self::format($periodo);
            throw new FailException("Período anterior [$anterior] é requerido para criar [$periodo]: $anteriorJsonFile");
        }
        
        $periodoJsonFile = Config::periodosJsonDir().$periodo.'.json';
        
        if(file_exists($periodoJsonFile)){
            $periodo = self::format($periodo);
            throw new FailException("Período [$periodo] já existe em [$periodoJsonFile]");
        }
        
        $data = [
            'periodo' => $periodo,
            'receitas' => [],
            'despesas' => [],
            'saldos' => [
                'periodo' => 0,
                'anterior' => 0,
                'acumulado' => 0
            ],
            'meta' => [
                'aberto' => true
            ]
        ];
        
        if($copiar !== null){
            //@todo
        }
        
        Json::write($data, $periodoJsonFile);
        
        return new PeriodoRecord($periodo);
    }
    
    public static function existe(string $periodo): bool {
        $periodoJsonFile = Config::periodosJsonDir().$periodo.'.json';
        return file_exists($periodoJsonFile);
    }
}
