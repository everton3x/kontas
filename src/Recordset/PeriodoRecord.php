<?php

namespace Kontas\Recordset;

use Kontas\Config\Config;
use Kontas\Exception\FailException;
use Kontas\Json\Json;
use Kontas\Util\Number;
use Kontas\Util\Periodo;

/**
 * Description of Periodo
 *
 * @author Everton
 */
class PeriodoRecord {
    protected string $periodo;
    protected array $data;
    
    
    public function __construct(string $periodo) {
        $this->periodo = $periodo;
        $this->read();
    }
    
    protected function read(): void {
        $filename = Config::periodosJsonDir(). $this->periodo.'.json';
        $this->data = Json::read($filename);
    }
    
    protected function salvar(): void {
        $filename = Config::periodosJsonDir(). $this->periodo.'.json';
        Json::write($this->data, $filename);
    }
    
    public function  periodo(): string {
        return $this->periodo;
    }
    
    public function format(): string {
        return Periodo::format($this->periodo);
    }
    
    public function receitaPrevistaTotal(bool $format = false) {
        $total = 0.0;
        
        foreach ($this->data['receitas'] as $item){
            foreach ($item['previsao'] as $subitem){
                $total += $subitem['valor'];
            }
        }
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
    }
    
    public function receitaRecebidaTotal(bool $format = false) {
        //@todo
        $total = 0.0;
        
        $total = rand(0,10000);
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
    }
    
    public function receitaAReceberTotal(bool $format = false) {
        $total = $this->receitaPrevistaTotal() - $this->receitaRecebidaTotal();
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
    }
    
    public function fechar(): void {
        
        if($this->aberto() === false){
            $atual = $this->format();
            throw new FailException("Não é possível fechar [$atual] porque ele já está fechado.");
        }
        
        $anterior = new PeriodoRecord(Periodo::anterior($this->periodo));
        if($anterior->aberto()){
            $anterior = $anterior->format();
            $atual = $this->format();
            throw new FailException("Não é possível fechar [$atual] porque [$anterior] ainda está aberto.");
        }
        
        //@todo
        //procedimentos de cálculos
        
        $this->data['meta']['aberto'] = false;
        
        $this->salvar();
    }
    
    public function aberto(): bool {
        switch ($this->data['meta']['aberto']){
            case 'true':
            case true:
                return true;
            case 'false':
            case false:
                return false;
            default:
                throw new FailException("Valor de meta.aberto é inválido: {$this->data['meta']['aberto']}");
        }
    }
}
