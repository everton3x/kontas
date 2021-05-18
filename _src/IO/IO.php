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
        $cli = new \League\CLImate\CLImate();
        
        $cli->info("Opções disponíveis:");
        foreach ($options as $index => $label){
            $cli->inline($index)->tab()->out($label);
        }
        
        $cli->out('Escolha uma opção:');
        
        if($default !== null){
            $cli->out("ENTER para padrão [$default]");
        }
        
        $input = $cli->input('>>>');
        
        $response = $input->prompt();
        
        if(key_exists($response, $options) === false){
            if($default !== null){
                $response = $default;
            }
        }
        
        if(key_exists($response, $options) === false){
            throw new FailException("Opção $response não reconhecida.");
        }
        
        return $response;
        
    }
    
    public static function confirm(string $msg): bool {
        $cli = new \League\CLImate\CLImate();
        $input = $cli->confirm($msg);
        return $input->confirmed();
    }
    
}
