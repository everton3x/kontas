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
        
        $cli->inline('Periodo:')
                ->tab()->bold()->green()
                ->out($this->rs->format());
        //@todo
        $cli->inline('Item')
                ->tab(2)->inline('Previsto')
                ->tab()->inline('Executado')
                ->tab()->inline('Saldo')
                ->br();
        $cli->inline('Receita')
                ->tab(2)->inline($this->rs->receitaPrevistaTotal(true))
                ->tab()->inline($this->rs->receitaRecebidaTotal(true))
                ->tab()->inline($this->rs->receitaAReceberTotal(true))
                ->br();
        $cli->inline('Despesa')
                ->tab(2)->inline($this->rs->despesaPrevistaTotal(true))
                ->tab()->inline($this->rs->despesaGastaTotal(true))
                ->tab()->inline($this->rs->despesaAGastarTotal(true))
                ->br();
        
        $cli->bold()->out('Saldos');
        $cli->inline('Anterior')
                ->tab()->out($this->rs->saldoAnterior(true));
        $cli->inline('Período')
                ->tab(2)->out($this->rs->saldoPeriodo(true));
        $cli->inline('Acumulado')
                ->tab()->out($this->rs->saldoAcumulado(true));
        
        $cli->bold()->inline('Aberto:')
                ->tab(2)->out($this->rs->aberto());
        
    }
    
    public static function lista(array $periodos): void {
        $cli = new CLImate();
        $cli->inline('Período')->tab()->out('Aberto');
        foreach ($periodos as $periodo => $instancia){
            $cli->inline(Periodo::format($periodo))->tab()->out($instancia->aberto());
        }
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
