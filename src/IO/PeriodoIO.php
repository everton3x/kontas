<?php

namespace Kontas\IO;

use Kontas\Recordset\PeriodoRecord;
use Kontas\Util\Periodo;
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
    
    public function resume(): void {
        $cli = new CLImate();
        $padding = $cli->padding(50);
        
        $cli->inline('Periodo:')
                ->tab()->bold()->green()
                ->out($this->rs->format());
        
        $cli->bold()->out('Receitas:');
        $padding->label('Previstas:')
                ->result($this->rs->receitaPrevistaTotal(true));
        $padding->label('Recebidas:')
                ->result($this->rs->receitaRecebidaTotal(true));
        $padding->label('A Receber:')
                ->result($this->rs->receitaAReceberTotal(true));
        $cli->br();
        
        $cli->bold()->out('Despesas:');
        $padding->label('Previstas:')
                ->result($this->rs->despesaPrevistaTotal(true));
        $padding->label('Gastos:')
                ->result($this->rs->despesaGastaTotal(true));
        $padding->label('A Gastar:')
                ->result($this->rs->despesaAGastarTotal(true));
        $padding->label('Paga:')
                ->result($this->rs->despesaPagaTotal(true));
        $padding->label('A Pagar:')
                ->result($this->rs->despesaAPagarTotal(true));
        $cli->br();
        
        $cli->bold()->out('Saldos');
        $padding->label('Anterior')
                ->result($this->rs->saldoAnterior(true));
        $padding->label('Período')
                ->result($this->rs->saldoPeriodo(true));
        $padding->label('Acumulado')
                ->result($this->rs->saldoAcumulado(true));
        
        $cli->bold()->inline('Aberto:')
                ->tab()->out($this->rs->aberto());
        
    }
    
    public static function lista(array $periodos): void {
        $cli = new CLImate();
        $cli->inline('Período')->tab()->out('Aberto');
        foreach ($periodos as $periodo => $instancia){
            $cli->inline(Periodo::format($periodo))->tab()->out($instancia->aberto());
        }
    }
    
    public function detalhe(): void {
        //@todo Mostra detalhadamente todas as receitas, despesas e saldos
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
