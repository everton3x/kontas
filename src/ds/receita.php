<?php

namespace kontas\ds;

/**
 * Description of receita
 *
 * @author Everton
 */
class receita {

    public static function addReceita(
            string $periodo,
            string $descricao,
            string $origem,
            string $devedor,
            string $cc,
            string $vencimento,
            string $agrupador,
            int $parcela,
            int $totalParcelas,
            float $valor,
            string $data,
            string $observacao
    ): int {
        
        if(mb_strlen($descricao) === 0){
            trigger_error('$descricao não pode ser vazio.', E_USER_ERROR);
        }
        if(mb_strlen($origem) === 0){
            trigger_error('$origem não pode ser vazio.', E_USER_ERROR);
        }
        if(mb_strlen($devedor) === 0){
            trigger_error('$devedor não pode ser vazio.', E_USER_ERROR);
        }
        if(mb_strlen($cc) === 0){
            trigger_error('$cc não pode ser vazio.', E_USER_ERROR);
        }
        if(mb_strlen($vencimento) === 0){
            trigger_error('$vencimento não pode ser vazio.', E_USER_ERROR);
        }
        if(mb_strlen($data) === 0){
            trigger_error('$data não pode ser vazio.', E_USER_ERROR);
        }
        if($valor <= 0){
            trigger_error('$valor deve ser maior que zero.', E_USER_ERROR);
        }
        if($parcela <= 0){
            trigger_error('$parcela deve ser maior que zero.', E_USER_ERROR);
        }
        if($totalParcelas <= 0){
            trigger_error('$totalParcelas deve ser maior que zero.', E_USER_ERROR);
        }
        
        if($totalParcelas < $parcela){
            trigger_error('$totalParcelas deve ser maior ou igual a $parcela.', E_USER_ERROR);
        }
        
        if(\kontas\util\periodo::periodoExists($periodo) === false){
            \kontas\ds\periodo::criar($periodo);
        }
        
        $data = \kontas\ds\periodo::load($periodo);
        $data['receitas'][] = [
            'descricao' => $descricao,
            'origem' => $origem,
            'devedor' => $devedor,
            'cc' => $cc,
            'vencimento' => $vencimento,
            'agrupador' => $agrupador,
            'parcela' => $parcela,
            'totalParcelas' => $totalParcelas,
            'previsao' => [[
                'valor' => $valor,
                'data' => $data,
                'observacao' => $observacao
            ]],
            'recebimento' => []
        ];
        \kontas\ds\periodo::save($data);
        return array_key_last($data['receitas']);
    }

}
