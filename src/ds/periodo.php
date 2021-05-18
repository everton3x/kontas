<?php

namespace kontas\ds;

/**
 * Description of periodo
 *
 * @author Everton
 */
class periodo {

    /**
     * 
     * @param string $periodo aaaa-mm
     * @return bool
     */
    public static function criar(string $periodo): bool {
        if (\kontas\util\periodo::periodoExists($periodo) === true) {
            trigger_error(sprintf("Período %s já existe.", \kontas\util\periodo::format($periodo)), E_USER_ERROR);
        }

        $anterior = \kontas\util\periodo::periodoAnterior($periodo);
        if (\kontas\util\periodo::periodoExists($anterior) === false) {
            trigger_error(sprintf("Período anterior %s não existe.", \kontas\util\periodo::format($anterior)), E_USER_ERROR);
        }

        $data = self::periodoDataStructure($periodo);
        $filename = \kontas\config::PERIODOS_DIR . $periodo . '.json';
        $save = \kontas\util\json::write($filename, $data);

        return $save;
    }

    protected static function periodoDataStructure(string $periodo): array {
        return [
            'periodo' => $periodo,
            'receitas' => [],
            'despesas' => [],
            'resultados' => [
                'periodo' => 0.0,
                'anterior' => 0.0,
                'acumulado' => 0.0
            ],
            'meta' => [
                'aberto' => true
            ]
        ];
    }

    public static function validate(array $data): bool {
        $key = self::testIfKeyExists(['periodo', 'receitas', 'despesas', 'resultados', 'meta'], $data);
        if ($key !== '') {
            trigger_error("Chave $key não encontrada no período.");
        }

        $key = self::testIfKeyExists(['periodo', 'anterior', 'acumulado'], $data['resultados']);
        if ($key !== '') {
            trigger_error("Chave resultados.$key não encontrada no período.");
        }

        $key = self::testIfKeyExists(['aberto'], $data['meta']);
        if ($key !== '') {
            trigger_error("Chave meta.$key não encontrada no período.");
        }

        if (sizeof($data['receitas']) > 0) {
            foreach ($data['receitas'] as $index => $item) {
                $key = self::testIfKeyExists(['descricao', 'origem', 'devedor', 'cc', 'vencimento', 'agrupador', 'parcela', 'totalParcelas', 'previsao', 'recebimento'], $item);
                if ($key !== '') {
                    trigger_error("Chave receitas.$index.$key não encontrada no período.");
                }
                $key = self::testIfValueNotIsEmpty(['descricao', 'origem', 'devedor', 'cc', 'vencimento'], $item);
                if ($key !== '') {
                    trigger_error("Chave receitas.$index.$key está vazia.");
                }
            }
        }

        if (sizeof($data['despesas']) > 0) {
            foreach ($data['despesas'] as $index => $item) {
                $key = self::testIfKeyExists(['descricao', 'aplicacao', 'projeto', 'agrupador', 'parcela', 'totalParcelas', 'previsao', 'gasto'], $item);
                if ($key !== '') {
                    trigger_error("Chave despesas.$index.$key não encontrada no período.");
                }
                $key = self::testIfValueNotIsEmpty(['descricao', 'aplicacao'], $item);
                if ($key !== '') {
                    trigger_error("Chave despesas.$index.$key está vazia.");
                }
            }
        }

        $key = self::testIfValueNotIsEmpty(['periodo'], $data);
        if ($key !== '') {
            trigger_error("Chave $key está vazia.");
        }

        return true;
    }

    protected static function testIfKeyExists(array $keys, array $data): string {
        foreach ($keys as $key) {
            if (key_exists($key, $data) === false) {
                return $key;
            }
        }

        return '';
    }

    protected static function testIfValueNotIsEmpty(array $keys, array $data): string {
        foreach ($keys as $key) {
            if (mb_strlen($data[$key]) === 0) {
                return $key;
            }
        }

        return '';
    }

}
