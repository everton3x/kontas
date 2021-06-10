<?php

namespace Kontas\Routine\Periodo;

/**
 * Description of ReabrirPeriodo
 *
 * @author Everton
 */
class ReabrirPeriodo extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Reabrir';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
    
    public function execute(): bool {
        $this->initialize();
        
        $periodo = new \PTK\Console\Form\Field\DateTimeField('periodo', 'Período', 'mY');
        $periodo->required(true);
        $periodo->setOutputFormat('m/Y');
        $periodo->setLabelInputFormat('mmaaaa');
        $periodo->ask();

        $ePeriodo = new \Kontas\Entity\Periodo($this->program);
        $ePeriodo->setPeriodo($periodo->object()->format('Y-m'));
        
        if ($ePeriodo->reopen() === false) {
            $this->program->console()->error("Período não reaberto: {$ePeriodo->get()->format('m/Y')}");
            $result = false;
        } else {
            $this->program->console()->info(sprintf('Período reaberto : %s', $ePeriodo->get()->format('m/Y')));
            $result = true;
        }

        $this->finalize();
        $this->program->pause();
        return $result;
    }
}
