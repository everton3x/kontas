<?php

namespace Kontas\Routine\Periodo;

/**
 * Description of FecharPeriodo
 *
 * @author Everton
 */
class FecharPeriodo extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Fechar';
    
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
        
        if ($ePeriodo->close() === false) {
            $this->program->console()->error("Período não fechado: {$ePeriodo->get()->format('m/Y')}");
            $result = false;
        } else {
            $this->program->console()->info(sprintf('Período fechado : %s', $ePeriodo->get()->format('m/Y')));
            $result = true;
        }

        $this->finalize();
        $this->program->pause();
        return $result;
    }
}
