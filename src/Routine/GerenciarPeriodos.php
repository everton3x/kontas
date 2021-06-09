<?php

namespace Kontas\Routine;

/**
 * Description of GerenciarPeriodos
 *
 * @author Everton
 */
class GerenciarPeriodos extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Período';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
