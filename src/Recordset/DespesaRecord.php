<?php

namespace Kontas\Recordset;

/**
 *
 * @author Everton
 */
class DespesaRecord {

    protected PeriodoRecord $periodo;

    public function __construct(PeriodoRecord $periodo) {
        $this->periodo = $periodo;
    }

    public function novaPrevisaoInicial(string $periodo, string $descricao, string $aplicacao, string $projeto, string $agrupador, float $valor, int $parcela, int $totalParcelas): array {
        
        return  [
            'descricao' => $descricao,
            'aplicacao' => $aplicacao,
            'agrupador' => $agrupador,
            'projeto' => $projeto,
            'parcela' => $parcela,
            'totalParcelas' => $totalParcelas,
            'previsao' => [[
                'valor' => $valor,
                'data' => date('Y-m-d'),
                'observacao' => 'Previsão inicial',
            ]],
            'gasto' => []
        ];
    }

}
