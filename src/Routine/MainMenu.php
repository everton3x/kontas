<?php

namespace Kontas\Routine;

/**
 * Menu principal do programa
 *
 * @author Everton
 */
class MainMenu extends \PTK\Console\Flow\Routine\RoutineAbstract {
    
    protected string $label = 'Início';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
