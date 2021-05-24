<?php

namespace kontas\io;

/**
 * Description of origem
 *
 * @author Everton
 */
class mp {
    
    public static function detail(int $key): void {
        $data = \kontas\ds\mp::listAll();
        $item = $data[$key];
        
        $climate = new \League\CLImate\CLImate();
        $climate->inline('Nome:')->tab(2)->green()->out($item['nome']);
        $climate->inline('Descrição:')->tab()->green()->out($item['descricao']);
        $climate->inline('Autopagar:')->tab()->green()->out($item['autopagar']);
        $climate->inline('Ativo:')->tab(2)->green()->out($item['ativo']);
    }
    
    public static function choice(int $status = -1): string {
        
        switch ($status){
            case -1:
                $list = \kontas\ds\mp::listAll();
                break;
            case 0:
                $list = \kontas\ds\mp::listInactive();
                break;
            case 1:
                $list = \kontas\ds\mp::listActive();
                break;
        }
        
        $climate = new \League\CLImate\CLImate();
        
        $climate->info('Meios de Pagamento:');
        foreach($list as $key => $item){
            $climate->inline($key)->tab()->out($item['nome']);
        }
        
        $climate->br();
        
        $climate->info('Selecione uma chave:');
        $input = $climate->input('>');
        $input->accept(array_keys($list));
        $input->strict();
        
        return $input->prompt();
    }
    
    public static function select(): string {
        
        $list = \kontas\ds\mp::listActive();
        
        $climate = new \League\CLImate\CLImate();
        
        $climate->info('Meios de Pagamento:');
        foreach($list as $key => $item){
            $climate->inline($key)->tab()->out($item['nome']);
        }
        
        $climate->br();
        
        $climate->info('Selecione uma chave:');
        $input = $climate->input('>');
        $input->accept(array_keys($list));
        $input->strict();
        
        $key = $input->prompt();
        
        if(key_exists($key, $list) === false){
            trigger_error("Chave $key não existente.", E_USER_ERROR);
        }
        
        return $list[$key]['nome'];
    }
    
    public static function choiceAutopagar(string $msg = 'Autopagar?'): bool {
        $climate = new \League\CLImate\CLImate();
        $input = $climate->confirm($msg);
        
        return $input->confirmed();
    }
}