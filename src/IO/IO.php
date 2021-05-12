<?php

namespace Kontas\IO;

/**
 * Description of IO
 *
 * @author Everton
 */
class IO {
    
    public static function input(string $msg, $default = null) {
        $cli = new \League\CLImate\CLImate();
        $cli->bold()->out($msg);
        $input = $cli->input('>>>');
        if($default !== null){
            $input->defaultTo($default);
        }
        
        return $input->prompt();
    }
    
    public static function choice(array $options, $default = null) {
        
    }
    
}
