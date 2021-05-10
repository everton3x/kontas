<?php

namespace Kontas\Origem\Command;

use Kontas\Command\CommandAbstract;
use Kontas\Origem\Origem;

/**
 * Description of OrigemCommand
 *
 * @author Everton
 */
class ListaOrigemCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Lista as origens cadastradas.', [
            'ativos' => [
                'prefix' => 'a',
                'longPrefix' => 'ativos',
                'description' => 'Exibe apenas os ativos.',
                'noValue' => true
            ],
            'inativos' => [
                'prefix' => 'i',
                'longPrefix' => 'inativos',
                'description' => 'Exibe apenas os inativos.',
                'noValue' => true
            ]
        ]);
    }

    public function execute(): void {
        $origem = new Origem();

        $lista = $origem->lista();

        if ($this->climate->arguments->get('ativos')) {
            $lista = $origem->listaAtivos();
        }

        if ($this->climate->arguments->get('inativos')) {
            $lista = $origem->listaInativos();
        }

        if ($this->climate->arguments->get('ativos') && $this->climate->arguments->get('inativos')) {
            $lista = $origem->lista();
        }

        $this->climate->flank("Origens cadastradas");

        $this->climate->table($lista);
    }

}
