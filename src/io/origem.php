<?php

namespace kontas\io;

/**
 * Description of origem
 *
 * @author Everton
 */
class origem {
    
    public static function detail(int $key): void {
        $data = \kontas\ds\origem::listAll();
        $item = $data[$key];
        
        $climate = new \League\CLImate\CLImate();
        $climate->inline('Nome:')->tab(2)->green()->out($item['nome']);
        $climate->inline('Descrição:')->tab()->green()->out($item['descricao']);
        $climate->inline('Ativo:')->tab(2)->green()->out($item['ativo']);
    }
}
