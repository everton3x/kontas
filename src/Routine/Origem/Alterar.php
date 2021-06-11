<?php

namespace Kontas\Routine\Origem;

class Alterar extends \PTK\Console\Flow\Routine\RoutineAbstract {

    protected string $label = 'Alteração';

    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }

    public function execute(): bool {
        $this->initialize();

        $options = [];
        $entity = new \Kontas\Entity\Origem($this->program);

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

        $ativo = new \PTK\Console\Form\Field\ChoiceField('ativo', 'Ativo', ['s' => true, 'n' => false]);
        switch ($entity->ativo()) {
            case true:
                $ativo->setDefault('s');
                break;
            case false:
                $ativo->setDefault('n');
                break;
        }
        $ativo->ask();

        $novoNome = $nome->answer();
        $novoAtivo = (int) $ativo->answer()[key($ativo->answer())];
        if ($novoNome === $entity->nome() && $novoAtivo == $entity->ativo()) {
            $result = true;
            $this->program->console()->info("Nenhuma alteração necessária.");
        } else {
            
            if($novoNome === $entity->nome()){
                $novoNome = null;
            }
            if($novoAtivo == $entity->ativo()){
                $novoAtivo = null;
            }
            
            if ($entity->update($novoNome, $novoAtivo) !== false) {
                $result = true;
                $entity->load($id);
                // @task Colocar uma tela com os detalhes da origem no lugar dessas mensagens
                $this->program->console()->info("Registro {$entity->nome()} atualizado com id {$entity->id()}.");
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
