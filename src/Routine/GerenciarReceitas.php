<?php

namespace Kontas\Routine;

class GerenciarReceitas extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Receita';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
