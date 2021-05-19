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
    
    public static function askDevedor(string $msg = 'Devedor:'): string {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        return $input->prompt();
    }
    
    public static function askCredor(string $msg = 'Credor:'): string {
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
    
    public static function askVencimento(string $msg = 'Vencimento [ddmmaaaa]:'): string {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        return $input->prompt();
    }
    
    public static function askAgrupador(string $msg = 'Agrupador (opcional):'): string {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        return $input->prompt();
    }
    
    public static function askValor(string $msg = 'Valor [####.##]:'): string {
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
    
    public static function choiceStatusOrAll(string $msg = 'Selecione o status:'): int {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        $input->accept(['a', 'i', 't'], true);
        $input->strict();
        $input->defaultTo('t');
        
        $choice = $input->prompt();
        
        switch ($choice){
            case 'a':
                return 1;
            case 'i':
                return 0;
            case 't':
                return -1;
        }
    }
}
