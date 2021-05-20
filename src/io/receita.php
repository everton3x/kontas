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

    public static function confirmRecebimento(string $data, float $valor, string $observacao): bool {
        $climate = new \League\CLImate\CLImate();
        $climate->inline('Data:')->tab()->bold()->green()->out(
                \kontas\util\date::format($data)
        );
        $climate->inline('Valor:')->tab(2)->bold()->green()->out(
                \kontas\util\number::format($valor)
        );
        $climate->inline('Observação:')->tab()->bold()->green()->out($observacao);

        $climate->br();

        $input = $climate->confirm('Confirma o recebimento?');

        return $input->confirmed();
    }

    public static function resume(string $periodo, array $data): void {
        $previsto = 0;
        $recebido = 0;

        foreach ($data['previsao'] as $item) {
            $previsto += $item['valor'];
        }
        foreach ($data['recebimento'] as $item) {
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

    public static function choice(string $periodo): int {
        $climate = new \League\CLImate\CLImate();
        $climate->info("Escolha uma receita:");

        $data = \kontas\ds\periodo::load($periodo);

        $list = $data['receitas'];

        foreach ($list as $key => $item) {
            $previsto = 0;
            $recebido = 0;
            foreach ($item['previsao'] as $prevItem) {
                $previsto += $prevItem['valor'];
            }
            foreach ($item['recebimento'] as $recItem) {
                $recebido += $recItem['valor'];
            }

            $climate->bold()->green()->inline($key)->tab()->bold()->green()->out($item['descricao']);
            $climate->inline('Origem:')->tab()->out($item['origem']);
            $climate->inline('Devedor:')->tab()->out($item['devedor']);
            $climate->inline('CC:')->tab()->out($item['cc']);
            $climate->inline($item['agrupador'])->tab()->out("({$item['parcela']}/{$item['totalParcelas']})");
            $climate->inline(\kontas\util\number::format($previsto))
                    ->tab()->inline(\kontas\util\number::format($recebido))
                    ->tab()->inline(\kontas\util\number::format($previsto - $recebido));
            $climate->br();
        }

        $climate->bold()->out('Escolha uma receita:');
        $input = $climate->input('>');
        $input->accept(array_keys($list));
        $input->strict();

        return $input->prompt();
    }

    public static function details(array $data): void {
        $climate = new \League\CLImate\CLImate();
        
        $climate->bold()->green()->out($data['descricao']);
        $climate->inline('Origem:')->tab()->out($data['origem']);
        $climate->inline('Devedor:')->tab()->out($data['devedor']);
        $climate->inline('CC:')->tab()->out($data['cc']);
        $climate->inline($data['agrupador'])->tab()->out("({$data['parcela']}/{$data['totalParcelas']})");

        $climate->bold()->out('Detalhes da previsão:');
        $previsto = 0;
        foreach ($data['previsao'] as $item) {
            $previsto += $item['valor'];
            $climate->tab()->inline('Data:')->tab()->out(
                    \kontas\util\date::format($item['data'])
            );
            $climate->tab()->inline('Valor:')->tab()->out(
                    \kontas\util\number::format($item['valor'])
            );
            $climate->tab()->inline('Observação:')->tab()->out($item['observacao']);
        }
        $climate->padding(KPADDING_LEN)->label('Total previsto')->result(
                \kontas\util\number::format($previsto)
        );

        $climate->bold()->out('Detalhes dos recebimentos:');
        $recebido = 0;
        foreach ($data['recebimento'] as $item) {
            $recebido += $item['valor'];
            $climate->tab()->inline('Data:')->tab()->out(
                    \kontas\util\date::format($item['data'])
            );
            $climate->tab()->inline('Valor:')->tab()->out(
                    \kontas\util\number::format($item['valor'])
            );
            $climate->tab()->inline('Observação:')->tab()->out($item['observacao']);
        }
        $climate->padding(KPADDING_LEN)->label('Total recebido')->result(
                \kontas\util\number::format($recebido)
        );
        $climate->padding(KPADDING_LEN)->label('A Receber')->result(
                \kontas\util\number::format($previsto - $recebido)
        );
    }

}
