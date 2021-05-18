<?php

namespace kontas\io;

/**
 * Description of generic
 *
 * @author Everton
 */
class generic {
    public static function askDescricao(string $msg = 'Descrição:'): string {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        return $input->prompt();
    }
    
    public static function askNome(string $msg = 'Nome:'): string {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        return $input->prompt();
    }
    
    public static function choiceStatus(string $msg = 'Selecione o status:'): bool {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        $input->accept(['a', 'i'], true);
        $input->strict();
        
        $choice = $input->prompt();
        
        switch ($choice){
            case 'a':
                return true;
            case 'i':
                return false;
        }
    }
}
