<?php

namespace Kontas\Aplicacao\Command;

use Kontas\Command\CommandAbstract;
use Kontas\Aplicacao\Aplicacao;

/**
 *
 * @author Everton
 */
class NovaAplicacaoCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Inclui nova aplicação da despesa.');
    }

    public function execute(): void {
        $input = $this->climate->input('Nome da aplicação: ');
        $nome = $input->prompt();

        $input = $this->climate->input('Descrição da aplicação (opcional): ');
        $descricao = $input->prompt();

        $aplicacao = new Aplicacao();
        $index = $aplicacao->adiciona($nome, $descricao);

        $record = $aplicacao->consulta($index);

        $this->climate->info('Aplicação cadastrada');
        $this->climate->inline('Nome:')->tab(2)->bold()->out($record['nome']);
        $this->climate->inline('Descrição:')->tab()->bold()->out($record['descricao']);
        $this->climate->inline('Ativo:')->tab(2)->bold()->out($record['ativo']);
    }

}
