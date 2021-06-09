<?php

namespace Kontas\Routine\Periodo;

/**
 * Description of Listar
 *
 * @author Everton
 */
class ListarPeriodos extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Listar';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
