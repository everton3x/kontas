<?php

namespace Kontas\Recordset;

use Kontas\Util\Periodo;

/**
 * Description of Periodo
 *
 * @author Everton
 */
class PeriodoRecord {
    protected string $periodo;
    
    public function __construct(string $periodo) {
        $this->periodo = $periodo;
    }
    
    public function  periodo(): string {
        return $this->periodo;
    }
    
    public function format(): string {
        return Periodo::format($this->periodo);
    }
}
