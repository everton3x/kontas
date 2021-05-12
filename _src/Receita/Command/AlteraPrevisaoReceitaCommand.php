<?php

namespace Kontas\Receita\Command;

use Kontas\Command\CommandAbstract;
use Kontas\Periodo\Periodo;

/**
 *
 * @author Everton
 */
class AlteraPrevisaoReceitaCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Altera o valor de uma previsão da receita.');
    }

    public function execute(): void {
        $input = $this->climate->input("Qual competência [AAAAMM]: ");
        $periodo = $input->prompt();
        
        $iPeriodo = new Periodo($periodo);
        $receitas = $iPeriodo->getReceitasDoPeriodo();
//        print_r($receitas);exit();
        $tabular = [];
        foreach ($receitas as $index => $item){
            $previsto = 0;
//            print_r($item['previsao']);exit();
            foreach ($item['previsao'] as $record){
                $previsto += $record['valor'];
            }
            
            $recebido = 0;
            //@todo
            $tabular[] = [
                'ID' => $index,
                'Receita' => $item['descricao'],
                'Previsto' => \Kontas\Util\Number::format($previsto),
                'Recebido' => \Kontas\Util\Number::format($recebido),
                'Saldo' => \Kontas\Util\Number::format($previsto - $recebido),
            ];
            $this->climate->table($tabular);
            $input = $this->climate->input('Qual receita?');
            $indexReceita = $input->prompt();
            
            if(key_exists($indexReceita, $receitas) === false){
                $this->climate->error("Índice [$indexReceita] não encontrado. Saindo...");
                exit();
            }
            
            $previsto = 0;
            foreach($receitas[$indexReceita]['previsao'] as $item){
                $previsto += $item['valor'];
            }
            
            $recebido = 0;
            //@todo
            
            $this->climate->border();
            $this->climate->flank('Receita selecionada');
            $this->climate->inline('Descrição:')->tab()->bold()->out($receitas[$indexReceita]['descricao']);
            $this->climate->inline('Origem:')->tab()->bold()->out($receitas[$indexReceita]['origem']);
            $this->climate->inline('Devedor:')->tab()->bold()->out($receitas[$indexReceita]['devedor']);
            $this->climate->inline('Centro de Custo:')->tab()->bold()->out($receitas[$indexReceita]['cc']);
            $this->climate->inline('Vencimento:')->tab()->bold()->out($receitas[$indexReceita]['vencimento']);
            $this->climate->inline('Agrupador:')->tab()->bold()->out($receitas[$indexReceita]['agrupador']);
            $this->climate->inline('Parcela:')->tab()->bold()->out($receitas[$indexReceita]['parcela']);
            $this->climate->inline('Total de parcelas:')->tab()->bold()->out($receitas[$indexReceita]['totalParcelas']);
            $this->climate->inline('Total previsto:')->tab()->bold()->out(\Kontas\Util\Number::format($previsto));
            $this->climate->inline('Total Recebido:')->tab()->bold()->out(\Kontas\Util\Number::format($recebido));
            $this->climate->border();
            $this->climate->bold()->out('Detalhes da previsão:');
            foreach ($receitas[$indexReceita]['previsao'] as $item){
                $this->climate->bold()->inline('Data')->tab(2);
                $this->climate->bold()->inline('Valor')->tab(2);
                $this->climate->bold()->inline('Observação');
                $this->climate->br()->border();
                $this->climate->inline($item['data'])->tab();
                $this->climate->inline(\Kontas\Util\Number::format($item['valor']))->tab();
                $this->climate->inline($item['observacao']);
                $this->climate->br();
            }
            $this->climate->border();
            
            $input = $this->climate->input('Digite o valor para aumentar/diminuir ([-]####.##):');
            $valor = $input->prompt();
            
            $input = $this->climate->input('Observação (opcional):');
            $observacao = $input->prompt();
            
            $receitas[$indexReceita]['previsao'][] = [
                'data' => date('Y-m-d'),
                'valor' => \Kontas\Util\Number::format($valor),
                'observacao' => $observacao
            ];
            
            $this->climate->border();
            $this->climate->info('Resumo da alteração:');
            $this->climate->inline('Previsão:')->tab()->bold()->out(\Kontas\Util\Number::format($previsto));
            $this->climate->inline('Alteração:')->tab()->bold()->out(\Kontas\Util\Number::format($valor));
            $this->climate->inline('Nova previsão:')->tab()->bold()->out(\Kontas\Util\Number::format($previsto + $valor));
            
            $confirm = $this->climate->confirm('Confirma a alteração?');
            if($confirm->confirmed()){
                //@todo
            }

        }
    }

}
