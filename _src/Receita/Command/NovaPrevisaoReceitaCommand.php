<?php

namespace Kontas\Receita\Command;

use Kontas\Command\CommandAbstract;
use Kontas\Periodo\Periodo;

/**
 *
 * @author Everton
 */
class NovaPrevisaoReceitaCommand extends CommandAbstract {

    public function __construct() {
        parent::__construct('Cadastra uma nova previsão da receita.');
    }

    public function execute(): void {
        $input = $this->climate->input("Qual competência [AAAAMM]: ");
        $periodo = $input->prompt();
        
        $input = $this->climate->input("Descrição: ");
        $descricao = $input->prompt();
        
        $input = $this->climate->input("Devedor: ");
        $devedor = $input->prompt();
        
        $origem = new \Kontas\Origem\Origem();
        $this->climate->flank('Origem da receita');
        $this->climate->table($origem->tabular());
        $this->climate->border();
        $input = $this->climate->input("Origem da receita: ");
        $origemIndex= $input->prompt();
        if(key_exists($origemIndex, $origem->listaAtivos()) === false){
            $this->climate->error("Índice [$origemIndex] não encontrado. Saindo...");
            exit();
        }
        $origem = $origem->listaAtivos()[$origemIndex]['nome'];
        
        $cc = new \Kontas\CC\CC();
        $this->climate->flank('Centro de custo');
        $this->climate->table($cc->tabular());
        $this->climate->border();
        $input = $this->climate->input("Centro de custo: ");
        $ccIndex= $input->prompt();
        if(key_exists($ccIndex, $cc->listaAtivos()) === false){
            $this->climate->error("Índice [$ccIndex] não encontrado. Saindo...");
            exit();
        }
        $cc = $cc->listaAtivos()[$ccIndex]['nome'];
        
        $input = $this->climate->input("Vencimento (opcional) [DDMMAAAA]: ");
        $input->defaultTo(\Kontas\Util\Date::ultimoDiaDoMes($periodo));
        $vencimento = $input->prompt();
        
        $input = $this->climate->input("Valor inicial (####.##): ");
        $valor = $input->prompt();
        
        $confirm = $this->climate->confirm("Lançar recebimento?");
        $receber = $confirm->confirmed();
        if($receber){
            //@todo
        }
        
        $this->climate->border();
        $this->climate->flank('Revisão dos dados');
        $this->climate->inline('Competência:')->tab(2)->bold()->out(Periodo::format($periodo));
        $this->climate->inline('Descrição:')->tab(2)->bold()->out($descricao);
        $this->climate->inline('Devedor:')->tab(2)->bold()->out($devedor);
        $this->climate->inline('Origem:')->tab(3)->bold()->out($origem);
        $this->climate->inline('Centro de custo:')->tab()->bold()->out($cc);
        $this->climate->inline('Vencimento:')->tab(2)->bold()->out(\Kontas\Util\Date::format($vencimento));
        $this->climate->inline('Valor:')->tab(3)->bold()->out(\Kontas\Util\Number::format($valor));
        
        if($receber){
            //@todo
        }
        
        $repeticoes = 1;
        
        $confirm = $this->climate->confirm("Deseja repetir nos meses seguintes?");
        if($confirm->confirmed()){
            $input = $this->climate->input("Quantos meses (contando a competência inicial)?");
            $repeticoes = $input->prompt();
        }
        
        $this->climate->border();
        $salvar = $this->climate->confirm("Confirma o lançamento?");
        if($salvar->confirmed()){
            for($i = 0; $i < $repeticoes;$i++){
                $competencia = new Periodo($periodo);
                $this->climate->info("Salvando competência ".Periodo::format($periodo));
                $competencia->adicionarPrevisaoReceita($descricao, $origem, $devedor, $cc, $vencimento, '', 1, 1, $valor);
                $periodo = Periodo::getPeriodoPosterior($periodo);
                $vencimento = \Kontas\Util\Date::proximoVencimento($vencimento);
                $competencia->salvar();
            }
        }
    }

}
