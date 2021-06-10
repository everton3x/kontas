<?php

namespace Kontas\Routine\Periodo;

/**
 * Description of DetalharPeriodo
 *
 * @author Everton
 * 
 */
class DetalharPeriodo extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Detalhar';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
    
    public function execute(): bool {
        // @task Mostrar todas as receitas/despesas e resultado, além do período e aberto/fechado
    }
}
