<?php

namespace kontas\util;

/**
 * Description of json
 *
 * @author Everton
 */
class json {
    
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
}
