<?php

namespace Kontas\MP\Command;

use Kontas\Command\CommandAbstract;
use Kontas\MP\MP;

/**
 *
 * @author Everton
 */
class DetalheMPCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Mostra detalhes do meio de pagamento.');
    }

    public function execute(): void {

        $mp = new MP();
        $ativos = [];
        $inativos = [];

        foreach ($mp->listaAtivos() as $index => $item) {
            $ativos[] = [
                'Id' => $index,
                'Nome' => $item['nome']
            ];
        }

        foreach ($mp->listaInativos() as $index => $item) {
            $inativos[] = [
                'Id' => $index,
                'Nome' => $item['nome']
            ];
        }

        if (sizeof($ativos)) {
            $this->climate->flank('Registros ativos');
            $this->climate->table($ativos);
        }

        if (sizeof($inativos)) {
            $this->climate->flank('Registros inativos');
            $this->climate->table($inativos);
        }

        $this->climate->info('Digite o ID que deseja detalhar:');
        $input = $this->climate->input('>>>');
        $index = $input->prompt();

        $record = $mp->consulta($index);
        $this->climate->flank('Detalhes do registro:');
        $this->climate->inline('Nome:')->tab(2)->bold()->out($record['nome']);
        $this->climate->inline('Descrição:')->tab()->bold()->out($record['descricao']);
        $this->climate->inline('Auto-pagamento:')->tab()->bold()->out($record['autopagar']);
        $this->climate->inline('Ativo:')->tab(2)->bold()->out($record['ativo']);
    }

}
