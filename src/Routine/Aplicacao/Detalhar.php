<?php

namespace Kontas\Routine\Aplicacao;

class Detalhar extends \PTK\Console\Flow\Routine\RoutineAbstract {

    protected string $label = 'Detalhe';

    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }

    public function execute(): bool {
        $this->initialize();

        $options = [];
        $entity = new \Kontas\Entity\Aplicacao($this->program);

        foreach ($entity->list() as $row) {
            $options[$row['id']] = $row['nome'];
        }

        $list = new \PTK\Console\Form\Field\ChoiceField('id', 'Id', $options);
        $list->setListTitle('Selecione um item da lista para detalhar...');
        $list->ask();
        $choice = $list->answer();
        $id = key($choice);
        
        $io = new \Kontas\IO\Aplicacao($this->program);
        $io->detalhar($id);

        $this->finalize();
        $this->program->pause();
        return true;
    }

}
