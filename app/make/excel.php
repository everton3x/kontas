<?php

require_once 'vendor/autoload.php';

function injetaNovaLinha(
        array $collection,
        int $ano,
        int $mes,
        string $categoria,
        string $operacao,
        string $descricao = '',
        string $origem = '',
        string $aplicacao = '',
        string $projeto = '',
        string $mp = '',
        string $pessoa = '',
        string $cc = '',
        string $vencimento = '',
        string $agrupador = '',
        int $parcela = 0,
        int $totalParcelas = 0,
        float $valor = 0.0,
        string $data = '',
        string $observacao = '',
        float $resultado = 0.00,
        float $anterior = 0.00,
        float $acumulado = 0.00,
        ?bool $aberto = null
): array {
    $collection[] = [
        'ano' => $ano,
        'mes' => $mes,
        'categoria' => $categoria,
        'operacao' => $operacao,
        'descricao' => $descricao,
        'origem' => $origem,
        'aplicacao' => $aplicacao,
        'projeto' => $projeto,
        'mp' => $mp,
        'pessoa' => $pessoa,
        'cc' => $cc,
        'vencimento' => $vencimento,
        'agrupador' => $agrupador,
        'parcela' => $parcela,
        'totalParcelas' => $totalParcelas,
        'valor' => $valor,
        'data' => $data,
        'observacao' => $observacao,
        'resultado' => $resultado,
        'anterior' => $anterior,
        'acumulado' => $acumulado,
        'aberto' => $aberto
    ];

    return $collection;
}

$climate = new \League\CLImate\CLImate();

try {
    $climate->info('Cria um arquivo do Excel com os dados tabulados...');

    $periodos = \kontas\ds\periodo::listAll();
    $collection = [];

    foreach ($periodos as $periodo => $status) {
        $climate->info("Processando $periodo...");
        $dados = \kontas\ds\periodo::load($periodo);
        $dt = DateTime::createFromFormat('Y-m', $periodo);
        $ano = $dt->format('Y');
        $mes = $dt->format('m');

        foreach ($dados['receitas'] as $item) {
            $climate->tab()->out('...receitas');
            $descricao = $item['descricao'];
            $origem = $item['origem'];
            $pessoa = $item['devedor'];
            $cc = $item['cc'];
            $dt = DateTime::createFromFormat('Y-m-d', $item['vencimento']);
            $vencimento = $dt->format('d/m/Y');
            $agrupador = $item['agrupador'];
            $parcela = $item['parcela'];
            $totalParcelas = $item['totalParcelas'];
            foreach ($item['previsao'] as $subitem) {
                $climate->tab(2)->out('...previsão');
                $valor = $subitem['valor'];
                $dt = DateTime::createFromFormat('Y-m-d', $subitem['data']);
                $data = $dt->format('d/m/Y');
                $observacao = $subitem['observacao'];
                $collection = injetaNovaLinha($collection, $ano, $mes, 'receita', 'previsao', $descricao, $origem, '', '', '', $pessoa, $cc, $vencimento, $agrupador, $parcela, $totalParcelas, $valor, $data, $observacao);
            }
            foreach ($item['recebimento'] as $subitem) {
                $climate->tab(2)->out('...recebimentos...');
                $valor = $subitem['valor'];
                $dt = DateTime::createFromFormat('Y-m-d', $subitem['data']);
                $data = $dt->format('d/m/Y');
                $observacao = $subitem['observacao'];
                $collection = injetaNovaLinha($collection, $ano, $mes, 'receita', 'recebimento', $descricao, $origem, '', '', '', $pessoa, $cc, $vencimento, $agrupador, $parcela, $totalParcelas, $valor, $data, $observacao);
            }
        }

        foreach ($dados['despesas'] as $item) {
            $climate->tab()->out('...despesas');
            $descricao = $item['descricao'];
            $aplicacao = $item['aplicacao'];
            $projeto = $item['projeto'];
            $agrupador = $item['agrupador'];
            $parcela = $item['parcela'];
            $totalParcelas = $item['totalParcelas'];
            foreach ($item['previsao'] as $subitem) {
                $climate->tab(2)->out('...previsão');
                $valor = $subitem['valor'];
                $dt = DateTime::createFromFormat('Y-m-d', $subitem['data']);
                $data = $dt->format('d/m/Y');
                $observacao = $subitem['observacao'];
                $collection = injetaNovaLinha($collection, $ano, $mes, 'despesa', 'previsao', $descricao, '', $aplicacao, $projeto, '', '', '', '', $agrupador, $parcela, $totalParcelas, $valor, $data, $observacao);
            }
            foreach ($item['gasto'] as $subitem) {
                $climate->tab(2)->out('...gastos');
                $pessoa = $subitem['credor'];
                $mp = $subitem['mp'];
                $dt = DateTime::createFromFormat('Y-m-d', $subitem['vencimento']);
                $vencimento = $dt->format('d/m/Y');
                $cc = $subitem['cc'];
                $valor = $subitem['valor'];
                $dt = DateTime::createFromFormat('Y-m-d', $subitem['data']);
                $data = $dt->format('d/m/Y');
                $observacao = $subitem['observacao'];
                $collection = injetaNovaLinha($collection, $ano, $mes, 'despesa', 'gasto', $descricao, '', $aplicacao, $projeto, $mp, $pessoa, $cc, $vencimento, $agrupador, $parcela, $totalParcelas, $valor, $data, $observacao);
                foreach ($subitem['pagamento'] as $subsubitem) {
                    $climate->tab(3)->out('...pagamentos');
                    $valor = $subsubitem['valor'];
                    $dt = DateTime::createFromFormat('Y-m-d', $subsubitem['data']);
                    $data = $dt->format('d/m/Y');
                    $observacao = $subsubitem['observacao'];
                    $collection = injetaNovaLinha($collection, $ano, $mes, 'despesa', 'pagamento', $descricao, '', $aplicacao, $projeto, $mp, $pessoa, $cc, $vencimento, $agrupador, $parcela, $totalParcelas, $valor, $data, $observacao);
                }
            }
        }
        
        $climate->tab()->out('...resultados');
        $collection = injetaNovaLinha($collection, $ano, $mes, 'resultados', 'periodo', '', '', '', '', '', '', '', '', '', 0, 0, $dados['resultados']['periodo']);
        $collection = injetaNovaLinha($collection, $ano, $mes, 'resultado', 'anterior', '', '', '', '', '', '', '', '', '', 0, 0, $dados['resultados']['anterior']);
        $collection = injetaNovaLinha($collection, $ano, $mes, 'resultados', 'acumulado', '', '', '', '', '', '', '', '', '', 0, 0, $dados['resultados']['acumulado']);
        
        $climate->tab()->out('...meta');
        $collection = injetaNovaLinha($collection, $ano, $mes, 'meta', 'aberto', '', '', '', '', '', '', '', '', '', 0, 0, 0.0, '', '', 0.0, 0.0, 0.0, $dados['meta']['aberto']);
    }

//    print_r($collection);
    
    $climate->info('Definindo nomes das colunas...');
    $colNames = array_keys($collection[array_key_first($collection)]);
    array_unshift($collection, $colNames);
        
    $filename = 'docs/output.xlsx';
    $climate->info("Salvando para XLSX em $filename ...");
    
    if(file_exists($filename)){
        unlink($filename);
    }
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->fromArray($collection);
    
    
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($filename);
    $climate->info('...Terminado!');
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}