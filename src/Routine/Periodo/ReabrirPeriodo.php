<?php

namespace Kontas\Routine\Periodo;

/**
 * Description of ReabrirPeriodo
 *
 * @author Everton
 */
class ReabrirPeriodo extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Reabrir';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
