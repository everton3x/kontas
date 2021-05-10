<?php

namespace Kontas\Origem\Command;

use Kontas\Command\CommandAbstract;
use Kontas\Origem\Origem;

/**
 * Description of OrigemCommand
 *
 * @author Everton
 */
class DetalheOrigemCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Mostra detalhes da origem da receita.');
    }

    public function execute(): void {

        $origem = new Origem();
        $ativos = [];
        $inativos = [];

        foreach ($origem->listaAtivos() as $index => $item) {
            $ativos[] = [
                'Id' => $index,
                'Nome' => $item['nome']
            ];
        }

        foreach ($origem->listaInativos() as $index => $item) {
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

        $record = $origem->consulta($index);
        $this->climate->flank('Detalhes do registro:');
        $this->climate->inline('Nome:')->tab(2)->bold()->out($record['nome']);
        $this->climate->inline('Descrição:')->tab()->bold()->out($record['descricao']);
        $this->climate->inline('Ativo:')->tab(2)->bold()->out($record['ativo']);
    }

}
