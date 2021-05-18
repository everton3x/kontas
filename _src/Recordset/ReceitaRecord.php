<?php

namespace Kontas\Recordset;

/**
 *
 * @author Everton
 */
class ReceitaRecord {
    
    
    public function novaPrevisaoInicial(
            string $periodo,
            string $descricao,
            string $origem,
            string $devedor,
            string $cc,
            string $vencimento,
            string $agrupador,
            float $valor,
            int $parcela,
            int $totalParcelas
    ): array {
        
        $data = [
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
                'data' => date('Y-m-d'),
                'observacao' => 'Previsão inicial'
            ]],
            'recebimento' => []
        ];
        
        return $data;
    }
    
    public function novaAlteracaoPrevisao(float $valor, string $observacao = ''): array {
        return [
            'data' => date('Y-m-d'),
            'valor' => $valor,
            'observacao' => $observacao
        ];
    }
    
    public function novoRecebimento(string $data, float $valor, string $observacao = ''): array {
        return [
            'data' => $data,
            'valor' => $valor,
            'observacao' => $observacao
        ];
    }
    
}
