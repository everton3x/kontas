<?php

namespace Kontas\Routine;

class GerenciarCadastros extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Cadastros';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
