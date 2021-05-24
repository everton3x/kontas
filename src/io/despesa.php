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

        if (key_exists($key, $data['despesas']) === false) {
            trigger_error("Chave $key não encontrada.", E_USER_ERROR);
        }

        $despesa = $data['despesas'][$key];

        $climate = new \League\CLImate\CLImate();

        $climate->bold()->green()->out($despesa['descricao']);
        $climate->inline('Aplicação:')->tab()->green()->out($despesa['aplicacao']);
        $climate->inline('Projeto:')->tab()->green()->out($despesa['projeto']);
        $climate->inline('Agrupador/Parcelas:')->tab()->green()->out("{$despesa['agrupador']}({$despesa['parcela']}/{$despesa['totalParcelas']})");

        $previsto = 0;
        foreach ($despesa['previsao'] as $item) {
            $previsto += $item['valor'];
        }
        $climate->padding(KPADDING_LEN)->label('Previsto')->result(
                \kontas\util\number::format($previsto)
        );

        $gasto = 0;
        $pago = 0;
        foreach ($despesa['gasto'] as $item) {
            $gasto += $item['valor'];
            foreach ($item['pagamento'] as $subitem) {
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

    /**
     * 
     * @param string $periodo aaaa-mm
     * @return array
     */
    public static function select(string $periodo): array {
        $despesas = \kontas\ds\despesa::listar($periodo);

        $climate = new \League\CLImate\CLImate();

        if (sizeof($despesas) === 0) {
            $climate->error(sprintf(
                            "Sem despesas para mostra no período %s",
                            \kontas\util\periodo::format($periodo)
            ));
            exit(0);
        }

        $climate->info(sprintf(
                        "Despesas do período %s",
                        \kontas\util\periodo::format($periodo)
        ));
        foreach ($despesas as $key => $item){
            \kontas\io\despesa::resume($periodo, $key);
        }
        
        $input = $climate->input('Selecione uma despesa:');
        $input->accept(array_keys($despesas));
        $input->strict();
        
        $key = $input->prompt();
        
        return $despesas[$key];
    }
    
    public static function choice(string $periodo): int {
        $despesas = \kontas\ds\despesa::listar($periodo);

        $climate = new \League\CLImate\CLImate();

        if (sizeof($despesas) === 0) {
            $climate->error(sprintf(
                            "Sem despesas para mostra no período %s",
                            \kontas\util\periodo::format($periodo)
            ));
            exit(0);
        }

        $climate->info(sprintf(
                        "Despesas do período %s",
                        \kontas\util\periodo::format($periodo)
        ));
        foreach ($despesas as $key => $item){
            $previsto = 0;
            foreach ($item['previsao'] as $subitem){
                $previsto += $subitem['valor'];
            }
            $gasto = 0;
            foreach ($item['gasto'] as $subitem){
                $gasto += $subitem['valor'];
            }
            
            $agastar = $previsto - $gasto;
            
            $previsto = \kontas\util\number::format($previsto);
            $gasto = \kontas\util\number::format($gasto);
            $agastar = \kontas\util\number::format($agastar);
            
            $climate->inline($key)->tab()->bold()->green()->out($item['descricao']);
            $climate->tab()->out("{$item['aplicacao']}/{$item['projeto']}");
            $climate->tab()->out("{$item['agrupador']} ({$item['parcela']}/{$item['totalParcelas']})");
            $climate->tab()->out("$previsto (-) $gasto (=) $agastar");
            $climate->br();
        }
        
        $input = $climate->input('Selecione uma despesa:');
        $input->accept(array_keys($despesas));
        $input->strict();
        
        $key = $input->prompt();
        
        return $key;
    }

}
