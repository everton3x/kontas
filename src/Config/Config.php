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
}
