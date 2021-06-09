<?php

namespace Kontas\Routine\Periodo;

/**
 * Description of FecharPeriodo
 *
 * @author Everton
 */
class FecharPeriodo extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Fechar';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
