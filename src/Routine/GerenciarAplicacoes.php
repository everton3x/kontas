<?php

namespace Kontas\Routine;

class GerenciarAplicacoes extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Aplicação da despesa';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
