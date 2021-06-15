<?php

namespace Kontas\Routine\MeioDePagamento;

class Alterar extends \PTK\Console\Flow\Routine\RoutineAbstract {

    protected string $label = 'Alteração';

    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }

    public function execute(): bool {
        $this->initialize();

        $options = [];
        $entity = new \Kontas\Entity\MeioDePagamento($this->program);

        foreach ($entity->list() as $row) {
            $options[$row['id']] = $row['nome'];
        }

        $list = new \PTK\Console\Form\Field\ChoiceField('id', 'Id', $options);
        $list->setListTitle('Selecione um item da lista para alterar...');
        $list->ask();
        $choice = $list->answer();
        $id = key($choice);
        $entity->load($id);

        $nome = new \PTK\Console\Form\Field\TextField('nome', 'Nome');
        $nome->setDefault($entity->nome())->ask();
        
        $autopagar = new \PTK\Console\Form\Field\YesNoField('autopagar', 'Autopagar?');
        $autopagar->setDefault($entity->autopagar());
        $autopagar->ask();

        $ativo = new \PTK\Console\Form\Field\ChoiceField('ativo', 'Ativo', ['a' => 'Ativo', 'i' => 'Inativo']);
        switch ($entity->ativo()) {
            case true:
                $ativo->setDefault('a');
                break;
            case false:
                $ativo->setDefault('i');
                break;
        }
        $ativo->ask();

        $novoNome = $nome->answer();
        switch (key($ativo->answer())) {
            case 'a':
                $novoAtivo = 1;
                break;
            case 'i':
                $novoAtivo = 0;
                break;
        }
        
        $novoAutopagar = $autopagar->answer();

        if ($novoNome === $entity->nome() && $novoAtivo == $entity->ativo() && $novoAutopagar == $entity->autopagar()) {
            $result = true;
            $this->program->console()->info("Nenhuma alteração necessária.");
        } else {

            if ($novoNome === $entity->nome()) {
                $novoNome = null;
            }
            if ($novoAtivo == $entity->ativo()) {
                $novoAtivo = null;
            }
            if ($novoAutopagar == $entity->autopagar()) {
                $novoAutopagar = null;
            }

            if ($entity->update($novoNome, $novoAutopagar, $novoAtivo) !== false) {
                $result = true;
                $entity->load($id);
                $this->program->console()->info("Registro {$entity->nome()} atualizado com id {$entity->id()}.")->br();
                $io = new \Kontas\IO\MeioDePagamento($this->program);
                $io->detalhar($entity->id());
            } else {
                $result = false;
                $this->program->console()->error("Registro {$nome->answer()} não foi atualizado.");
            }
        }


        $this->finalize();
        $this->program->pause();
        return $result;
    }

}
