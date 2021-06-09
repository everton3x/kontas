<?php

namespace Kontas\Routine\Periodo;

/**
 * Description of ResumirPeriodo
 *
 * @author Everton
 */
class ResumirPeriodo extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Resumir';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
