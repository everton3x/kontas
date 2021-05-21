<?php

namespace kontas\ds;

/**
 * Description of despesa
 *
 * @author Everton
 */
class despesa {

    /**
     * 
     * @param string $periodo aaaa-mm
     * @param string $descricao
     * @param string $aplicacao
     * @param string $projeto
     * @param string $agrupador
     * @param int $parcela
     * @param int $totalParcelas
     * @param float $valor
     * @param string $data aaaa-mm-dd
     * @param string $observacao
     * @return int
     */
    public static function addDespesa(
            string $periodo,
            string $descricao,
            string $aplicacao,
            string $projeto,
            string $agrupador,
            int $parcela,
            int $totalParcelas,
            float $valor,
            string $data,
            string $observacao
    ): int {
        
        if(\kontas\util\periodo::periodoExists($periodo) === false){
            \kontas\ds\periodo::criar($periodo);
        }
        
        if(mb_strlen($descricao) === 0){
            trigger_error('$descricao não pode ser vazio.', E_USER_ERROR);
        }
        if(mb_strlen($aplicacao) === 0){
            trigger_error('$aplicacao não pode ser vazio.', E_USER_ERROR);
        }
        if(mb_strlen($projeto) === 0){
            trigger_error('$aplicacao não pode ser vazio.', E_USER_ERROR);
        }
        
        $periodoData = \kontas\ds\periodo::load($periodo);
        
        $periodoData['despesas'][] = [
            'descricao' => $descricao,
            'aplicacao' => $descricao,
            'projeto' => $descricao,
            'agrupador' => $descricao,
            'parcela' => $descricao,
            'totalParcelas' => $descricao,
            'previsao' => [[
                'valor' => $valor,
                'data' => $data,
                'observacao' => $observacao,
            ]],
            'gasto' => [],
        ];
        
        $key = array_key_last($periodoData['despesas']);
        
        \kontas\ds\periodo::save($periodoData);
        
        return $key;
    }

}
