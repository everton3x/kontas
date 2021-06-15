<?php

namespace Kontas\Routine\Aplicacao;

class Adicionar extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Adicionar';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
    
    public function execute(): bool {
        $this->initialize();
        
        $nome = new \PTK\Console\Form\Field\TextField('nome', 'Nome');
        $nome->required(true)->ask();
        
        $entity = new \Kontas\Entity\Aplicacao($this->program);
        $id = $entity->add($nome->answer());

        if($id !== false){
            $result = true;
            $entity->load($id);
            $this->program->console()->info("Registro {$entity->nome()} criado com id {$entity->id()}.")->br();
            $io = new \Kontas\IO\Aplicacao($this->program);
            $io->detalhar($entity->id());
        }else{
            $result = false;
            $this->program->console()->error("Registro {$nome->answer()} não foi criado.");
        }
        
        $this->finalize();
        $this->program->pause();
        return $result;
    }
}
