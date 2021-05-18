<?php

namespace kontas\io;

/**
 * Description of periodo
 *
 * @author Everton
 */
class periodo {
    
    public static function inPeriodo(string $msg = 'Período [mmaaaa]'): string {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        return $input->prompt();
    }
}
