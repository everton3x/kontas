<?php

namespace Kontas\Routine;

class GerenciarProjetos extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Projetos';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
