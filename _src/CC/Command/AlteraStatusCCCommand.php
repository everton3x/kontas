<?php

namespace Kontas\CC\Command;

use Kontas\Command\CommandAbstract;
use Kontas\CC\CC;

/**
 *
 * @author Everton
 */
class AlteraStatusCCCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Altera o status do centro de custos.');
    }

    public function execute(): void {

        $cc = new CC();
        $ativos = [];
        $inativos = [];

        foreach ($cc->listaAtivos() as $index => $item) {
            $ativos[] = [
                'Id' => $index,
                'Nome' => $item['nome']
            ];
        }

        foreach ($cc->listaInativos() as $index => $item) {
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

        $this->climate->info('Digite o ID que deseja alterar:');
        $input = $this->climate->input('>>>');
        $index = $input->prompt();

        $record = $cc->consulta($index);
        $this->climate->flank('Registro para alterar:');
        $this->climate->inline('Nome:')->tab()->bold()->out($record['nome']);
        $this->climate->inline('Descrição:')->tab()->bold()->out($record['descricao']);
        $this->climate->inline('Ativo:')->tab()->bold()->out($record['ativo']);

        $this->climate->info('Escolha uma opção:');
        $input = $this->climate->input('>>>');
        $input->accept(['a', 'i'], true);

        $choice = $input->prompt();

        switch (mb_strtolower($choice)) {
            case 'a':
                $ativo = true;
                break;
            case 'i':
                $ativo = false;
                break;
            default :
                $this->climate->error("A opção [$choice] não é válida.");
                exit();
        }

        $cc->alteraStatus($index, $ativo);

        $record = $cc->consulta($index);

        $this->climate->flank('Registro alterado:');
        $this->climate->inline('Nome:')->tab(2)->bold()->out($record['nome']);
        $this->climate->inline('Descrição:')->tab()->bold()->out($record['descricao']);
        $this->climate->inline('Ativo:')->tab(2)->bold()->out($record['ativo']);
    }

}
