<?php

namespace Kontas\Periodo;

use DateInterval;
use Exception;
use Kontas\Config\Config;
use Kontas\Json\Json;

/**
 * Description of Origem
 *
 * @author Everton
 */
class Periodo {

    protected string $filename;
    protected array $data;

    public function __construct(string $periodo) {
        $this->filename = Config::getPeriodosJsonDir().$periodo.'.json';
        
        $this->data = Json::read($this->filename);
    }
    
    public static function criar(string $periodo, string $copiar = ''): Periodo {
        
        self::testaStringPeriodo($periodo);
        
        $anterior = self::getPeriodoAnterior($periodo);
        $filename = Config::getPeriodosJsonDir().$anterior.'.json';
        if(file_exists($filename) == false){
            throw new Exception("Período anterior [$periodo] não existe: $filename");
        }
        
        $filename = Config::getPeriodosJsonDir().$periodo.'.json';
        if(file_exists($filename)){
            throw new Exception("Período [$periodo] já existe: $filename");
        }
        
        $data = [
            'periodo' => $periodo,
            'receitas' => [],
            'despesas' => [],
            'saldos' => [],
            'meta' => [
                'aberto' => true
            ],
        ];
        
        if($copiar !== ''){
            $data = self::copiarPeriodo($copiar, $periodo);
        }
        
        Json::write($data, $filename);
        
        return new Periodo($periodo);
        
    }
    
    public static function testaStringPeriodo(string $periodo): void {
        if(mb_strlen($periodo) !== 6){
            throw new Exception("Tamanho do período [$periodo] é inválido: ". mb_strlen($periodo));
        }
        
        try{
            $date = date_create_from_format('Ym', $periodo);
        } catch (Exception $ex) {
            throw new Exception("Período [$periodo] não origina uma data válida.");
        }
    }
    
    protected static function copiarPeriodo(string $from, string $to): array {
        self::testaStringPeriodo($to);
        self::testaStringPeriodo($from);
    }
    
    public static function getPeriodoAnterior(string $periodo): string {
        $date = date_create_from_format('Ym', $periodo);
        $date->sub(new DateInterval('P1M'));
        return $date->format('Ym');
    }
    
    public static function getPeriodoPosterior(string $periodo): string {
        $date = date_create_from_format('Ym', $periodo);
        $date->add(new DateInterval('P1M'));
        return $date->format('Ym');
    }

    public function valida(array $data): void {
        
    }

    public function salvar(): void {
        $this->valida($this->data);

        Json::write($this->data, $this->filename);
    }
    
    public static function format(string $periodo): string {
        $date = date_create_from_format('Ym', $periodo);
        return $date->format('m/Y');
    }
    
    public function adicionarPrevisaoReceita(string $descricao, string $origem, string $devedor, string $cc, string $vencimento, string $agrupador, int $parcela, int $totalParcelas, float $valor): void {
        $vencimento = date_create_from_format('dmY', $vencimento);
        $this->data['receitas'][] = [
            'descricao' => $descricao,
            'origem' => $origem,
            'devedor' => $devedor,
            'cc' => $cc,
            'vencimento' => $vencimento->format('Y-m-d'),
            'agrupador' => $agrupador,
            'parcela' => $parcela,
            'totalParcelas' => $totalParcelas,
            'previsao' => [[
                'valor' => $valor,
                'data' => date('Y-m-d'),
                'observacao' => 'Previsão inicial'
            ]]
        ];
    }
    
    public function getReceitasDoPeriodo(): array {
        return $this->data['receitas'];
    }
}
