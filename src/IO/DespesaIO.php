<?php

namespace Kontas\IO;

use Kontas\Exception\FailException;
use Kontas\Recordset\PeriodoRecord;
use Kontas\Util\Date;
use Kontas\Util\Number;
use League\CLImate\CLImate;

/**
 * Description of DespesaIO
 *
 * @author Everton
 */
class DespesaIO {
    protected PeriodoRecord $rs;
    
    public function __construct(PeriodoRecord $rs) {
        $this->rs = $rs;
    }
    
    public function detalhes(int $index): void {
        $cli = new CLImate();
        $padding = $cli->padding(40);
        $despesas = $this->rs->despesas();
        if(key_exists($index, $despesas) === false){
            throw new FailException("Despesa não encontrada para o índice [$index].");
        }
        $data = $despesas[$index];
        
        $cli->bold()->out('Detalhes da despesa:');
        $cli->inline('Período:')->tab(2)->bold()->out($this->rs->format());
        $cli->inline('Descrição:')->tab(2)->bold()->out($data['descricao']);
        $cli->inline('Aplicação:')->tab(3)->bold()->out($data['aplicacao']);
        $cli->inline('Agrupador:')->tab(2)->bold()->out($data['agrupador']);
        $cli->inline('Parcela:')->tab(2)->bold()->out("{$data['parcela']}/{$data['totalParcelas']}");
        
        $cli->bold()->out('Previsão:');
        foreach ($data['previsao'] as $key => $item){
            $cli->inline('Id:')->tab()->bold()->out($key);
            $cli->tab()->inline('Data:')->tab(2)->bold()->out(Date::format($item['data']));
            $cli->tab();
            $padding->label('Valor:')->result(Number::format($item['valor']));
            $cli->tab()->inline('Observação:')->tab()->bold()->out($item['observacao']);
        }
        $padding->label('Total Previsto:')->result($this->rs->receitaPrevistaTotal(true));
        
        
        
        
    }
}
