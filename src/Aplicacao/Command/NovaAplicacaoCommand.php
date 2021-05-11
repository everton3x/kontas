<?php

namespace Kontas\Origem\Command;

use Kontas\Command\CommandAbstract;
use Kontas\Origem\Origem;

/**
 * Description of OrigemCommand
 *
 * @author Everton
 */
class NovaOrigemCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Inclui nova origem da receita.');
    }

    public function execute(): void {
        $input = $this->climate->input('Nome da origem: ');
        $nome = $input->prompt();

        $input = $this->climate->input('Descrição da origem (opcional): ');
        $descricao = $input->prompt();

        $origem = new Origem();
        $index = $origem->adiciona($nome, $descricao);

        $record = $origem->consulta($index);

        $this->climate->info('Origem cadastrada');
        $this->climate->inline('Nome:')->tab(2)->bold()->out($record['nome']);
        $this->climate->inline('Descrição:')->tab()->bold()->out($record['descricao']);
        $this->climate->inline('Ativo:')->tab(2)->bold()->out($record['ativo']);
    }

}
