<?php

namespace Kontas\Periodo\Command;

use Kontas\Command\CommandAbstract;
use Kontas\Periodo\Periodo;

/**
 *
 * @author Everton
 */
class NovoPeriodoCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Cria um novo período.');
    }

    public function execute(): void {
        $input = $this->climate->input('Período (AAAAMM): ');
        $periodo = $input->prompt();

        $input = $this->climate->input('Período para copiar (opcional): ');
        $copiar = $input->prompt();

        
        $periodo = Periodo::criar($periodo, $copiar);

//        $record = $origem->consulta($index);
//
//        $this->climate->info('Origem cadastrada');
//        $this->climate->inline('Nome:')->tab(2)->bold()->out($record['nome']);
//        $this->climate->inline('Descrição:')->tab()->bold()->out($record['descricao']);
//        $this->climate->inline('Ativo:')->tab(2)->bold()->out($record['ativo']);
    }

}
