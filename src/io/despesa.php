<?php

namespace kontas\io;

/**
 * Description of despesa
 *
 * @author Everton
 */
class despesa {
    /**
     * 
     * @param string $periodo aaaa-mm
     * @param int $key
     * @return void
     */
    public static function resume(string $periodo, int $key): void {
        $data = \kontas\ds\periodo::load($periodo);
        
        if(key_exists($key, $data['despesas']) === false){
            trigger_error("Chave $key não encontrada.", E_USER_ERROR);
        }
        
        $despesa = $data['despesas'][$key];
        
        $climate = new \League\CLImate\CLImate();
        
        $climate->bold()->green()->out($despesa['descricao']);
        $climate->inline('Aplicação:')->tab()->green()->out($despesa['aplicacao']);
        $climate->inline('Projeto:')->tab()->green()->out($despesa['projeto']);
        $climate->inline('Agrupador/Parcelas:')->tab()->green()->out("{$despesa['agrupador']}({$despesa['parcela']}/{$despesa['totalParcelas']})");
        
        $previsto = 0;
        foreach($despesa['previsao'] as $item){
            $previsto += $item['valor'];
        }
        $climate->padding(KPADDING_LEN)->label('Previsto')->result(
                \kontas\util\number::format($previsto)
        );
        
        $gasto = 0;
        $pago = 0;
        foreach($despesa['gasto'] as $item){
            $gasto += $item['valor'];
            foreach ($item['pagamento'] as $subitem){
                $pago += $subitem['valor'];
            }
        }
        $climate->padding(KPADDING_LEN)->label('Gasto')->result(
                \kontas\util\number::format($gasto)
        );
        $climate->padding(KPADDING_LEN)->label('A Gastar')->result(
                \kontas\util\number::format($previsto - $gasto)
        );
        $climate->padding(KPADDING_LEN)->label('Pago')->result(
                \kontas\util\number::format($pago)
        );
        $climate->padding(KPADDING_LEN)->label('A Pagar')->result(
                \kontas\util\number::format($gasto - $pago)
        );
    }
    
    public static function askCredor(string $msg = 'Credor:'): string {
        $climate = new \League\CLImate\CLImate();
        $climate->out($msg);
        $input = $climate->input('>');
        return $input->prompt();
    }
}
