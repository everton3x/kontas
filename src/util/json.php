<?php

namespace kontas\util;

/**
 * Description of json
 *
 * @author Everton
 */
class json {
    
    /**
     * 
     * @param string $filename
     * @param array $data
     * @return bool
     */
    public static function write(string $filename, array $data): bool {
        \kontas\ds\periodo::validate($data);
        $json = json_encode($data);
        if($json === false){
            trigger_error(json_last_error_msg(), E_USER_ERROR);
        }
        
        $printer = new \Ergebnis\Json\Printer\Printer();
        $json = $printer->print($json);
        
        $save = file_put_contents($filename, $json);
        if($save === false){
            trigger_error("Falha ao salvar dados JSON: $filename", E_USER_ERROR);
        }
        
        return (bool) $save;
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return array
     */
    public static function load(string $periodo): array {
        $data = [];
        
        if(\kontas\util\periodo::periodoExists($periodo) === false){
            trigger_error("Perído $periodo não existe.");
        }
        
        $filename = \kontas\config::PERIODOS_DIR.$periodo.'.json';
        
        $json = file_get_contents($filename);
        if($json === false){
            trigger_error("Falha ao ler o conteúdo de $filename");
        }
        
        $data = json_decode($json, true);
        if($data === false){
            trigger_error(json_last_error_msg());
        }
        
        return $data;
    }
}
