<?php

namespace Kontas\IO;

use Kontas\Exception\FailException;
use Kontas\Recordset\PeriodoRecord;
use Kontas\Util\Date;
use Kontas\Util\Number;
use League\CLImate\CLImate;

/**
 *
 * @author Everton
 */
class ReceitaIO {
    protected PeriodoRecord $rs;
    
    public function __construct(PeriodoRecord $rs) {
        $this->rs = $rs;
    }
    
    public function detalhes(int $index): void {
        $cli = new CLImate();
        $padding = $cli->padding(40);
        $receitas = $this->rs->receitas();
        if(key_exists($index, $receitas) === false){
            throw new FailException("Receita não encontrada para o índice [$index].");
        }
        $data = $receitas[$index];
        
        $cli->bold()->out('Detalhes da receita:');
        $cli->inline('Período:')->tab(2)->bold()->out($this->rs->format());
        $cli->inline('Descrição:')->tab(2)->bold()->out($data['descricao']);
        $cli->inline('Origem:')->tab(3)->bold()->out($data['origem']);
        $cli->inline('Devedor:')->tab(2)->bold()->out($data['devedor']);
        $cli->inline('Centro de custo:')->tab()->bold()->out($data['cc']);
        $cli->inline('Vencimento:')->tab(2)->bold()->out(Date::format($data['vencimento']));
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
        
        $cli->bold()->out('Recebimentos:');
        foreach ($data['recebimento'] as $key => $item){
            $cli->inline('Id:')->tab()->bold()->out($key);
            $cli->tab()->inline('Data:')->tab(2)->bold()->out(Date::format($item['data']));
            $cli->tab();
            $padding->label('Valor:')->result(Number::format($item['valor']));
            $cli->tab()->inline('Observação:')->tab()->bold()->out($item['observacao']);
        }
        $padding->label('Total Recebido:')->result($this->rs->receitaRecebidaTotal(true));
        
        $cli->br();
        
        $padding->label('A Receber:')->result($this->rs->receitaAReceberTotal(true));
        
        
    }
    
    public function select(): int {
        $receitas = $this->rs->receitas();
        $cli = new CLImate();
        
        foreach ($receitas as $index => $item){
            $previsto = 0;
            foreach ($item['previsao'] as $previsao){
                $previsto += $previsao['valor'];
            }
            
            $recebido = 0;
            foreach ($item['recebimento'] as $recebimento){
                $recebido += $recebimento['valor'];
            }
            
            $saldo = Number::format($previsto - $recebido);
            $previsto = Number::format($previsto);
            $recebido = Number::format($recebido);
            
            $cli->bold()->inline($index)->tab()->out($item['descricao']);
            $cli->tab()->out("{$item['origem']}/{$item['devedor']}/{$item['cc']}");
            $cli->tab()->out("{$item['agrupador']} ({$item['parcela']}/{$item['totalParcelas']})");
            $cli->tab()->out("$previsto (-) $recebido (=) $saldo");
        }
        
        $index = IO::input('Escolha uma receita:');
        
        if(key_exists($index, $receitas) === false){
            throw new FailException("Índice [$index] não encontrado.");
        }
        
        return $index;
    }
    
    
}
