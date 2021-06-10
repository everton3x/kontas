<?php

namespace Kontas\Routine;

class GerenciarMeiosDePagamento extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Meio de Pagamento';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
}
