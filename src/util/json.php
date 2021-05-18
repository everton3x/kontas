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
     * @param string $filename
     * @return array
     */
    public static function load(string $filename): array {
        $data = [];
        
        if(file_exists($filename) === false){
            trigger_error("Arquivo $filename não existe.");
        }
        
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
