<?php

namespace Kontas\CC\Command;

use Kontas\Command\CommandAbstract;
use Kontas\CC\CC;

/**
 *
 * @author Everton
 */
class ListaCCCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Lista os centros de custos cadastrados.', [
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
        $cc = new CC();

        $lista = $cc->lista();

        if ($this->climate->arguments->get('ativos')) {
            $lista = $cc->listaAtivos();
        }

        if ($this->climate->arguments->get('inativos')) {
            $lista = $cc->listaInativos();
        }

        if ($this->climate->arguments->get('ativos') && $this->climate->arguments->get('inativos')) {
            $lista = $cc->lista();
        }

        if(sizeof($lista) === 0){
            $this->climate->info("Nada para mostrar");
            return;
        }
        
        $this->climate->flank("Centros de custos cadastrados");

        $this->climate->table($lista);
    }

}
