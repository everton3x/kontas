<?php

namespace Kontas\Origem\Command;

use Kontas\Command\CommandAbstract;
use Kontas\Origem\Origem;

/**
 * Description of OrigemCommand
 *
 * @author Everton
 */
class AlteraStatusOrigemCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Altera o status da origem da receita.');
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

        $this->climate->info('Digite o ID que deseja alterar:');
        $input = $this->climate->input('>>>');
        $index = $input->prompt();

        $record = $origem->consulta($index);
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
                $this->climate->error("A opção [$choice] não é vãlida.");
                exit();
        }

        $origem->alteraStatus($index, $ativo);

        $record = $origem->consulta($index);

        $this->climate->flank('Registro alterado:');
        $this->climate->inline('Nome:')->tab(2)->bold()->out($record['nome']);
        $this->climate->inline('Descrição:')->tab()->bold()->out($record['descricao']);
        $this->climate->inline('Ativo:')->tab(2)->bold()->out($record['ativo']);
    }

}
