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
}
