<?php

namespace Kontas\Routine;

class GerenciarCentrosDeCustos extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Centro de Custo';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
