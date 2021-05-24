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
//        $filename = \kontas\config::PERIODOS_DIR . $periodo . '.json';
//        $save = \kontas\util\json::write($filename, $data);
        $save = self::save($data);

        return $save;
    }

    public static function save(array $data): bool {
        $periodo = $data['periodo'];
        self::validate($data);
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
        $key = \kontas\util\check::testIfKeyExists(['periodo', 'receitas', 'despesas', 'resultados', 'meta'], $data);
        if ($key !== '') {
            trigger_error("Chave $key não encontrada no período.");
        }

        $key = \kontas\util\check::testIfKeyExists(['periodo', 'anterior', 'acumulado'], $data['resultados']);
        if ($key !== '') {
            trigger_error("Chave resultados.$key não encontrada no período.");
        }

        $key = \kontas\util\check::testIfKeyExists(['aberto'], $data['meta']);
        if ($key !== '') {
            trigger_error("Chave meta.$key não encontrada no período.");
        }

        if (sizeof($data['receitas']) > 0) {
            foreach ($data['receitas'] as $index => $item) {
                $key = \kontas\util\check::testIfKeyExists(['descricao', 'origem', 'devedor', 'cc', 'vencimento', 'agrupador', 'parcela', 'totalParcelas', 'previsao', 'recebimento'], $item);
                if ($key !== '') {
                    trigger_error("Chave receitas.$index.$key não encontrada no período.");
                }
                $key = \kontas\util\check::testIfValueNotIsEmpty(['descricao', 'origem', 'devedor', 'cc', 'vencimento'], $item);
                if ($key !== '') {
                    trigger_error("Chave receitas.$index.$key está vazia.");
                }
            }
        }

        if (sizeof($data['despesas']) > 0) {
            foreach ($data['despesas'] as $index => $item) {
                $key = \kontas\util\check::testIfKeyExists(['descricao', 'aplicacao', 'projeto', 'agrupador', 'parcela', 'totalParcelas', 'previsao', 'gasto'], $item);
                if ($key !== '') {
                    trigger_error("Chave despesas.$index.$key não encontrada no período.");
                }
                $key = \kontas\util\check::testIfValueNotIsEmpty(['descricao', 'aplicacao'], $item);
                if ($key !== '') {
                    trigger_error("Chave despesas.$index.$key está vazia.");
                }
            }
        }

        $key = \kontas\util\check::testIfValueNotIsEmpty(['periodo'], $data);
        if ($key !== '') {
            trigger_error("Chave $key está vazia.");
        }

        return true;
    }

    public static function periodoIsOpen(string $periodo): bool {
        $data = \kontas\util\json::load($periodo);

        switch ($data['meta']['aberto']) {
            case true:
            case 'true':
                return true;
            case false:
            case 'false':
                return false;
            default:
                trigger_error("Chave meta.aberto tem valor inválido.");
        }
    }

    public static function close(string $periodo): bool {
        if (\kontas\util\periodo::periodoExists($periodo) === false) {
            trigger_error(sprintf("Período %s não existe", \kontas\util\periodo::format($periodo)), E_USER_ERROR);
        }

        $anterior = \kontas\util\periodo::periodoAnterior($periodo);
        if (self::isOpened($anterior) === true) {
            trigger_error(sprintf(
                            "Não é possível encerrar %s se %s estiver aberto.",
                            \kontas\util\periodo::format($periodo),
                            \kontas\util\periodo::format($anterior)
                    ), E_USER_ERROR);
        }

        $filename = \kontas\config::PERIODOS_DIR . $periodo . '.json';
        $data = \kontas\util\json::load($filename);
        $data = self::calcResultadosFor($data);
        $data['meta']['aberto'] = false;

        return \kontas\util\json::write($filename, $data);
    }

    /**
     * 
     * @param array $data
     * @return array
     */
    public static function calcResultadosFor(array $data): array {
        $receitas = 0;
        foreach ($data['receitas'] as $receita){
            foreach ($receita['previsao'] as $item){
                $receitas += $item['valor'];
            }
        }
        
        $despesas = 0;
        foreach ($data['despesas'] as $despesa){
            foreach ($despesa['previsao'] as $item){
                $despesas += $item['valor'];
            }
        }
        
        $resultado = $receitas - $despesas;
        
        $periodoAnterior = self::load(\kontas\util\periodo::periodoAnterior($data['periodo']));
        $anterior = $periodoAnterior['resultados']['acumulado'];
        
        
        $data['resultados']['periodo'] = $resultado;
        $data['resultados']['anterior'] = $anterior;
        $data['resultados']['acumulado'] = $anterior + $resultado;
        
        return $data;
    }

    public static function isOpened(string $periodo): bool {
        if (\kontas\util\periodo::periodoExists($periodo) === false) {
            trigger_error(sprintf("Período %s não existe", \kontas\util\periodo::format($periodo)), E_USER_ERROR);
        }

        $filename = \kontas\config::PERIODOS_DIR . $periodo . '.json';
        $data = \kontas\util\json::load($filename);

        switch ($data['meta']['aberto']) {
            case true:
            case 'true':
                return true;
            case false:
            case 'false':
                return false;
            default:
                trigger_error("Chave meta.aberto tem valor inválido.");
        }
    }

    public static function load(string $periodo): array {
        if (\kontas\util\periodo::periodoExists($periodo) === false) {
            trigger_error(sprintf("Período %s não existe", \kontas\util\periodo::format($periodo)), E_USER_ERROR);
        }

        $filename = \kontas\config::PERIODOS_DIR . $periodo . '.json';
        return \kontas\util\json::load($filename);
    }

    public static function reopen(string $periodo): bool {
        if (\kontas\util\periodo::periodoExists($periodo) === false) {
            trigger_error(sprintf("Período %s não existe", \kontas\util\periodo::format($periodo)), E_USER_ERROR);
        }

        $posterior = \kontas\util\periodo::periodoPosterior($periodo);
        if (\kontas\util\periodo::periodoExists($posterior) === true) {
            if (self::isOpened($posterior) === false) {
                trigger_error(sprintf(
                                "Não é possível reabrir %s se %s estiver fechado.",
                                \kontas\util\periodo::format($periodo),
                                \kontas\util\periodo::format($posterior)
                        ), E_USER_ERROR);
            }
        }

        $filename = \kontas\config::PERIODOS_DIR . $periodo . '.json';
        $data = \kontas\util\json::load($filename);
        $data['meta']['aberto'] = true;

        return \kontas\util\json::write($filename, $data);
    }

    public static function listAll(): array {
        $list = [];
        foreach (scandir(\kontas\config::PERIODOS_DIR) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $periodo = basename($item, '.json');
            $list[$periodo] = self::isOpened($periodo);
        }
        return $list;
    }

    public static function listOpened(): array {
        $list = [];
        foreach (self::listAll() as $key => $item) {
            if ($item === true) {
                $list[$key] = $item;
            }
        }

        return $list;
    }

    public static function listClosed(): array {
        $list = [];
        foreach (self::listAll() as $key => $item) {
            if ($item === false) {
                $list[$key] = $item;
            }
        }

        return $list;
    }

}
