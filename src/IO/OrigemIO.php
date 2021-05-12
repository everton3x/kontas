<?php

namespace Kontas\IO;

/**
 * Description of OrigemIO
 *
 * @author Everton
 */
class OrigemIO {
    protected \Kontas\Repo\OrigensRepo $repo;
    
    public function __construct(\Kontas\Repo\OrigensRepo $repo) {
        $this->repo = $repo;
    }
    
    public function detail(int $index): void {
        $data = $this->repo->record($index);
        $cli = new \League\CLImate\CLImate();
        
        $cli->out('Nome:');
        $cli->bold()->green()->out($data['nome']);
        
        $cli->out('Descrição:');
        $cli->bold()->green()->out($data['descricao']);
        
        $cli->out('Ativo:');
        $cli->bold()->green()->out($data['ativo']);
    }
}
