<?php

namespace Kontas\IO;

use Kontas\Repo\CcRepo;
use League\CLImate\CLImate;

/**
 * Description of CCIO
 *
 * @author Everton
 */
class CcIO {
    protected CcRepo $repo;
    
    public function __construct(CcRepo $repo) {
        $this->repo = $repo;
    }
    
    public function detail(int $index): void {
        $data = $this->repo->record($index);
        $cli = new CLImate();
        
        $cli->inline('Nome:')->tab(2)->bold()->green()->out($data['nome']);
        
        $cli->inline('Descrição:')->tab()->bold()->green()->out($data['descricao']);
        
        $cli->inline('Ativo:')->tab(2)->bold()->green()->out($data['ativo']);
    }
    
    public function select(?bool $status = null): int {
        $options = [];

        if($status == null){
            foreach($this->repo->list() as $index => $item){
                $options[$index] = $item['nome'];
            }
        }
        
        if($status == true){
            foreach($this->repo->listAtivos() as $index => $item){
                $options[$index] = $item['nome'];
            }
        }
        
        if($status == false){
            foreach($this->repo->listInativos() as $index => $item){
                $options[$index] = $item['nome'];
            }
        }
        
        return IO::choice($options);
    }
}
