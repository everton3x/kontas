<?php

namespace Kontas\Routine\Origem;

class Adicionar extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Adicionar';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
    
    public function execute(): bool {
        $this->initialize();
        
        $nome = new \PTK\Console\Form\Field\TextField('nome', 'Nome');
        $nome->required(true)->ask();
        
        $entity = new \Kontas\Entity\Origem($this->program);
        $id = $entity->add($nome->answer());

        if($id !== false){
            $result = true;
            $entity->load($id);
            // @task Colocar uma tela com os detalhes da origem no lugar dessas mensagens
            $this->program->console()->info("Registro {$entity->nome()} criado com id {$entity->id()}.");
        }else{
            $result = false;
            $this->program->console()->error("Registro {$nome->answer()} não foi criado.");
        }
        
        $this->finalize();
        $this->program->pause();
        return $result;
    }
}
