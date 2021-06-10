<?php

namespace Kontas\Routine;

class GerenciarOrigens extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Origem da receita';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
