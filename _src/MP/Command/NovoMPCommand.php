<?php

namespace Kontas\MP\Command;

use Kontas\Command\CommandAbstract;
use Kontas\MP\MP;

/**
 *
 * @author Everton
 */
class NovoMPCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Inclui novo meio de pagamento.');
    }

    public function execute(): void {
        $input = $this->climate->input('Nome do meio de pagamentos: ');
        $nome = $input->prompt();

        $input = $this->climate->input('Descrição do meio de pagamentos (opcional): ');
        $descricao = $input->prompt();

        $input = $this->climate->confirm('Auto-pagar?');
        $autopagar = $input->confirmed();
        
        $mp = new MP();
        $index = $mp->adiciona($nome, $descricao, $autopagar);

        $record = $mp->consulta($index);

        $this->climate->info('Meio de pagamento cadastrado');
        $this->climate->inline('Nome:')->tab(2)->bold()->out($record['nome']);
        $this->climate->inline('Descrição:')->tab()->bold()->out($record['descricao']);
        $this->climate->inline('Auto-pagar:')->tab()->bold()->out($record['autopagar']);
        $this->climate->inline('Ativo:')->tab(2)->bold()->out($record['ativo']);
    }

}
