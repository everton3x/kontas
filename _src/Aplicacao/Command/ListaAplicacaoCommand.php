<?php

namespace Kontas\Aplicacao\Command;

use Kontas\Command\CommandAbstract;
use Kontas\Aplicacao\Aplicacao;

/**
 *
 * @author Everton
 */
class ListaAplicacaoCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Lista as aplicações cadastradas.', [
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

        if(sizeof($lista) === 0){
            $this->climate->info("Nada para mostrar");
            return;
        }
        
        $this->climate->flank("Aplicações cadastradas");

        $this->climate->table($lista);
    }

}
