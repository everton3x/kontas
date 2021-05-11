<?php

namespace Kontas\MP\Command;

use Kontas\Command\CommandAbstract;
use Kontas\MP\MP;

/**
 *
 * @author Everton
 */
class ListaMPCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Lista os meios de pagamento cadastrados.', [
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
        $mp = new MP();

        $lista = $mp->lista();

        if ($this->climate->arguments->get('ativos')) {
            $lista = $mp->listaAtivos();
        }

        if ($this->climate->arguments->get('inativos')) {
            $lista = $mp->listaInativos();
        }

        if ($this->climate->arguments->get('ativos') && $this->climate->arguments->get('inativos')) {
            $lista = $mp->lista();
        }

        if(sizeof($lista) === 0){
            $this->climate->info("Nada para mostrar");
            return;
        }
        
        $this->climate->flank("Meios de pagamento cadastrados");

        $this->climate->table($lista);
    }

}
