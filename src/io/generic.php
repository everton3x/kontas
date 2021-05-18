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
}
