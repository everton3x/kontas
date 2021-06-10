<?php

namespace Kontas\Routine\Periodo;

/**
 * Description of AbrirPeriodo
 *
 * @author Everton
 */
class AbrirPeriodo extends \PTK\Console\Flow\Routine\RoutineAbstract {

    protected string $label = 'Abrir';

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
        if ($ePeriodo->open($periodo->object()->format('Y-m')) === false) {
            $this->program->console()->error("Período não criado: $periodo");
            $result = false;
        } else {
            $this->program->console()->info(sprintf('Período cadastrado: %s', $ePeriodo->get()->format('m/Y')));
            $result = true;
        }

        $this->finalize();
        $this->program->pause();
        return $result;
    }

}
