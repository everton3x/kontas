<?php

namespace kontas\io;

/**
 * Description of periodo
 *
 * @author Everton
 */
class periodo {

    public static function askPeriodo(string $msg = 'Período [mmaaaa]'): string {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        return $input->prompt();
    }

    public static function resume(array $data): void {
        $climate = new \League\CLImate\CLImate();
        $climate->bold()->green()->out(
                \kontas\util\periodo::format($data['periodo'])
        );
        $climate->padding(KPADDING_LEN)->label('Aberto:')->result($data['meta']['aberto']);
    }

}
