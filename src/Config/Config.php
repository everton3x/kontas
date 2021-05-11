<?php

namespace Kontas\Config;

/**
 * Description of Config
 *
 * @author Everton
 */
class Config {
    public static function getOrigemJsonFile(): string {
        return 'data/auxiliar/origem.json';
    }
    
    public static function getCCJsonFile(): string {
        return 'data/auxiliar/cc.json';
    }
    
    public static function getAplicacaoJsonFile(): string {
        return 'data/auxiliar/aplicacao.json';
    }
    
    public static function getMPJsonFile(): string {
        return 'data/auxiliar/mp.json';
    }
}
