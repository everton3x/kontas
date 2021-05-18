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
    
    public static function list(int $status = -1): void {
        switch ($status){
            case 1:
                $list = \kontas\ds\periodo::listOpened();
                break;
            case 0:
                $list = \kontas\ds\periodo::listClosed();
                break;
            case -1:
                $list = \kontas\ds\periodo::listAll();
                break;
        }
        
        $climate = new \League\CLImate\CLImate();
        
        foreach ($list as $periodo => $status){
            $climate->padding(KPADDING_LEN)->label(\kontas\util\periodo::format($periodo))->result($status);
        }
    }
    
    public static function choiceStatus(string $msg = 'Selecione o status:'): int {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        $input->accept(['a', 'f', 't'], true);
        $input->strict();
        $input->defaultTo('t');
        
        $choice = $input->prompt();
        
        switch ($choice){
            case 'a':
                return 1;
            case 'f':
                return 0;
            case 't':
                return -1;
        }
    }

}
