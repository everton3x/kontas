<?php

namespace Kontas\Routine\CentroDeCusto;

class Listar extends \PTK\Console\Flow\Routine\RoutineAbstract {

    protected string $label = 'Lista';

    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }

    public function execute(): bool {
        $this->initialize();

        $entity = new \Kontas\Entity\CentroDeCusto($this->program);
        
        $choice = new \PTK\Console\Form\Field\ChoiceField('ativo', 'Mostrar', ['t' => 'Todos', 'a' => 'Só ativos', 'i' => 'Só inativos']);
        $choice->setDefault('t')->setListTitle('Escolha o que mostrar:');
        $choice->ask();
        
        switch (key($choice->answer())){
            case 't':
                $stmt = null;
                break;
            case 'a':
                $stmt = $this->program->dbh()->prepare('SELECT * FROM cc WHERE ativo = 1 ORDER BY nome ASC');
                break;
            case 'i':
                $stmt = $this->program->dbh()->prepare('SELECT * FROM cc WHERE ativo = 0 ORDER BY nome ASC');
                break;
        }
        
        $this->program->console()->inline('Legenda:')->tab()->inline('Ativo')->tab()->red()->out('Inativo')->br();
        
        foreach ($entity->list($stmt) as $row){
            switch ($row['ativo']){
                case "1":
                    $this->program->console()->inline($row['id'])->tab()->out($row['nome']);
                    break;
                case "0":
                    $this->program->console()->red()->inline($row['id'])->tab()->red()->out($row['nome']);
                    break;
            }
            
        }


        $this->finalize();
        $this->program->pause();
        return true;
    }

}
