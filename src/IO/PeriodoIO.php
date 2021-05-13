<?php

namespace Kontas\IO;

use Kontas\Recordset\PeriodoRecord;
use League\CLImate\CLImate;

/**
 *
 * @author Everton
 */
class PeriodoIO {
    protected PeriodoRecord $rs;
    
    public function __construct(PeriodoRecord $rs) {
        $this->rs = $rs;
    }
    
    public function detail(): void {
        $cli = new CLImate();
        
        $cli->inline('Periodo:')->tab()->bold()->green()->out($this->rs->format());
        //@todo
        //total de receita prevista, arrecadada e saldo
        //total de despesa prevista, arrecadada e saldo
        //saldos
        //aberto/fechado
        
    }
    
//    public function select(?bool $status = null): int {
//        $options = [];
//
//        if($status == null){
//            foreach($this->repo->list() as $index => $item){
//                $options[$index] = $item['nome'];
//            }
//        }
//        
//        if($status == true){
//            foreach($this->repo->listAtivos() as $index => $item){
//                $options[$index] = $item['nome'];
//            }
//        }
//        
//        if($status == false){
//            foreach($this->repo->listInativos() as $index => $item){
//                $options[$index] = $item['nome'];
//            }
//        }
//        
//        return IO::choice($options);
//    }
}
