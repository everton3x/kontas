<?php

namespace Kontas\Config;

/**
 *
 * @author Everton
 */
class Config {
    public static function origemJsonFile(): string {
        return 'data/auxiliar/origem.json';
    }
    
    public static function ccJsonFile(): string {
        return 'data/auxiliar/cc.json';
    }
    
    public static function aplicacaoJsonFile(): string {
        return 'data/auxiliar/aplicacao.json';
    }
    
    public static function projetoJsonFile(): string {
        return 'data/auxiliar/projeto.json';
    }
    
    public static function mpJsonFile(): string {
        return 'data/auxiliar/mp.json';
    }
    
    public static function periodosJsonDir(): string {
        return 'data/periodos/';
    }
}
