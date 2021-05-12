<?php

namespace Kontas\CC\Command;

use Kontas\Command\CommandAbstract;
use Kontas\CC\CC;

/**
 *
 * @author Everton
 */
class NovoCCCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Inclui novo centro de custos.');
    }

    public function execute(): void {
        $input = $this->climate->input('Nome do centro de custos: ');
        $nome = $input->prompt();

        $input = $this->climate->input('Descrição do centro de custos (opcional): ');
        $descricao = $input->prompt();

        $cc = new CC();
        $index = $cc->adiciona($nome, $descricao);

        $record = $cc->consulta($index);

        $this->climate->info('Centro de custos cadastrado');
        $this->climate->inline('Nome:')->tab(2)->bold()->out($record['nome']);
        $this->climate->inline('Descrição:')->tab()->bold()->out($record['descricao']);
        $this->climate->inline('Ativo:')->tab(2)->bold()->out($record['ativo']);
    }

}
