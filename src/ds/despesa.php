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

        if (\kontas\util\periodo::periodoExists($periodo) === false) {
            \kontas\ds\periodo::criar($periodo);
        }

        if (mb_strlen($descricao) === 0) {
            trigger_error('$descricao não pode ser vazio.', E_USER_ERROR);
        }
        if (mb_strlen($aplicacao) === 0) {
            trigger_error('$aplicacao não pode ser vazio.', E_USER_ERROR);
        }
        if (mb_strlen($projeto) === 0) {
            trigger_error('$aplicacao não pode ser vazio.', E_USER_ERROR);
        }

        $periodoData = \kontas\ds\periodo::load($periodo);

        $periodoData['despesas'][] = [
            'descricao' => $descricao,
            'aplicacao' => $aplicacao,
            'projeto' => $projeto,
            'agrupador' => $agrupador,
            'parcela' => $parcela,
            'totalParcelas' => $totalParcelas,
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

    public static function gastar(
            string $periodo,
            int $despesa,
            string $credor,
            string $mp,
            string $vencimento,
            string $cc,
            float $valor,
            string $data,
            string $observacao
    ): int {
        
        if (mb_strlen($credor) === 0) {
            trigger_error('$credor não pode ser vazio.', E_USER_ERROR);
        }
        if (mb_strlen($mp) === 0) {
            trigger_error('$mp não pode ser vazio.', E_USER_ERROR);
        }
        if (mb_strlen($cc) === 0) {
            trigger_error('$cc não pode ser vazio.', E_USER_ERROR);
        }

        $periodoData = \kontas\ds\periodo::load($periodo);

        if (key_exists($despesa, $periodoData['despesas']) === false) {
            trigger_error("Chave $despesa não encontrada.", E_USER_ERROR);
        }

        $periodoData['despesas'][$despesa]['gasto'][] = [
            'credor' => $credor,
            'mp' => $mp,
            'vencimento' => $vencimento,
            'cc' => $cc,
            'valor' => $valor,
            'data' => $data,
            'pagamento' => []
        ];

        $key = array_key_last($periodoData['despesas'][$despesa]['gasto']);

        \kontas\ds\periodo::save($periodoData);

        return $key;
    }

    public static function pagar(
            string $periodo,
            int $despesa,
            int $gasto,
            float $valor,
            string $data,
            string $observacao
    ): int {
        $periodoData = \kontas\ds\periodo::load($periodo);

        if (key_exists($despesa, $periodoData['despesas']) === false) {
            trigger_error("Chave $despesa não encontrada.", E_USER_ERROR);
        }
        
        if (key_exists($gasto, $periodoData['despesas'][$despesa]['gasto']) === false) {
            trigger_error("Chave $gasto não encontrada.", E_USER_ERROR);
        }

        $periodoData['despesas'][$despesa]['gasto'][$gasto]['pagamento'][] = [
            'valor' => $valor,
            'data' => $data,
            'observacao' => $observacao
        ];

        $key = array_key_last($periodoData['despesas'][$despesa]['gasto'][$gasto]['pagamento']);

        \kontas\ds\periodo::save($periodoData);

        return $key;
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return array
     */
    public static function listar(string $periodo): array {
        $data = \kontas\ds\periodo::load($periodo);
        
        return $data['despesas'];
    }

    /**
     * 
     * @param string $periodo aaaa-mm
     * @param int $despesa
     * @param float $valor
     * @param string $data aaaa-mm-dd
     * @param string $observacao
     * @return int
     */
    public static function alteraPrevisaoDespesa(string $periodo, int $despesa, float $valor, string $data, string $observacao): int {
        $periodoData = \kontas\ds\periodo::load($periodo);
        
        if(key_exists($despesa, $periodoData['despesas']) === false){
            trigger_error("Chave $despesa não encontrada.", E_USER_ERROR);
        }
        
        $periodoData['despesas'][$despesa]['previsao'][] = [
            'valor' => $valor,
            'data' => $data,
            'observacao' => $observacao
        ];
        
        \kontas\ds\periodo::save($periodoData);
        
        return array_key_last($periodoData['despesas'][$despesa]['previsao']);
    }

}
