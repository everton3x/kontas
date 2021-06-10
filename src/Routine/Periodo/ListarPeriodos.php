<?php

namespace Kontas\Routine\Periodo;

/**
 * Description of Listar
 *
 * @author Everton
 */
class ListarPeriodos extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Listar';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
    
    public function execute(): bool {
        $this->initialize();
        
        $status = new \PTK\Console\Form\Field\ChoiceField('status', "Status", ['Todos', 'Só abertos', 'Só fechados']);
        $status->setDefault(0);
        $status->ask();
        
        $aberto = null;
        switch (key($status->answer())){
            case 1:
                $aberto = 1;
                break;
            case 2:
                $aberto = 0;
                break;
        }
        
        $io = new \Kontas\IO\Periodo($this->program);
        $io->listar($aberto);
        
        $this->finalize();
        $this->program->pause();
        return true;
    }
}
