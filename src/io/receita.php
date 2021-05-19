<?php

namespace kontas\io;

/**
 * Description of receita
 *
 * @author Everton
 */
class receita {
    public static function confirm(array $data): bool {
        $climate = new \League\CLImate\CLImate();
        $climate->inline('Período:')->tab()->bold()->green()->out(
                \kontas\util\periodo::format($data['periodo'])
        );
        $climate->inline('Descrição:')->tab()->bold()->green()->out($data['descricao']);
        $climate->inline('Origem:')->tab(2)->bold()->green()->out($data['origem']);
        $climate->inline('Devedor:')->tab()->bold()->green()->out($data['devedor']);
        $climate->inline('CC:')->tab(2)->bold()->green()->out($data['cc']);
        $climate->inline('Vencimento:')->tab()->bold()->green()->out(
                \kontas\util\date::format($data['vencimento'])
        );
        $climate->inline('Agrupador:')->tab()->bold()->green()->out($data['agrupador']);
        $climate->inline('Valor:')->tab(2)->bold()->green()->out(
                \kontas\util\number::format($data['valor'])
        );
        
        $climate->br();
        
        $input = $climate->confirm('Confirma os valores?');
        
        return $input->confirmed();
    }
    
    public static function resume(string $periodo, array $data): void {
        $previsto = 0;
        $recebido = 0;
        
        foreach($data['previsao'] as $item){
            $previsto += $item['valor'];
        }
        foreach($data['recebimento'] as $item){
            $recebido += $item['valor'];
        }
        
        $climate = new \League\CLImate\CLImate();
        $climate->inline('Período:')->tab()->bold()->green()->out(
                \kontas\util\periodo::format($periodo)
        );
        $climate->inline('Descrição:')->tab()->bold()->green()->out($data['descricao']);
        $climate->inline('Origem:')->tab(2)->bold()->green()->out($data['origem']);
        $climate->inline('Devedor:')->tab()->bold()->green()->out($data['devedor']);
        $climate->inline('CC:')->tab(2)->bold()->green()->out($data['cc']);
        $climate->inline('Vencimento:')->tab()->bold()->green()->out(
                \kontas\util\date::format($data['vencimento'])
        );
        $climate->inline('Agrupador:')->tab()->bold()->green()->out($data['agrupador']);
        $climate->inline('Previsto:')->tab(2)->bold()->green()->out(
                \kontas\util\number::format($previsto)
        );
        $climate->inline('Recebido:')->tab(2)->bold()->green()->out(
                \kontas\util\number::format($recebido)
        );
        $climate->inline('A Receber:')->tab(2)->bold()->green()->out(
                \kontas\util\number::format($previsto - $recebido)
        );
        
        
    }
}
