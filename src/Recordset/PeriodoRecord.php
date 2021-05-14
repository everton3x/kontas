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
    
    protected function valida(): void {
        
    }
    
    protected function salvar(): void {
        $this->valida();
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
    
    public function despesaPrevistaTotal(bool $format = false) {
        $total = 0.0;
        
        //@todo
        $total = rand(0,10000);
        
//        foreach ($this->data['despesas'] as $item){
//            foreach ($item['previsao'] as $subitem){
//                $total += $subitem['valor'];
//            }
//        }
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
    }
    
    public function despesaGastaTotal(bool $format = false) {
        $total = 0.0;
        
        //@todo
        $total = rand(0,10000);
        
//        foreach ($this->data['despesas'] as $item){
//            foreach ($item['previsao'] as $subitem){
//                $total += $subitem['valor'];
//            }
//        }
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
    }
    
    public function despesaPagaTotal(bool $format = false) {
        $total = 0.0;
        
        //@todo
        $total = rand(0,10000);
        
//        foreach ($this->data['despesas'] as $item){
//            foreach ($item['previsao'] as $subitem){
//                $total += $subitem['valor'];
//            }
//        }
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
    }
    
    public function despesaAGastarTotal(bool $format = false) {
        $total = $this->despesaPrevistaTotal() - $this->despesaGastaTotal();
        
        
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
    }
    
    public function despesaAPagarTotal(bool $format = false) {
        $total = $this->despesaGastaTotal() - $this->despesaPagaTotal();
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
    }
    
    public function receitaRecebidaTotal(bool $format = false) {
        //@todo
        $total = 0.0;
        
        foreach ($this->data['receitas'] as $item){
            foreach ($item['recebimento'] as $subitem){
                $total += $subitem['valor'];
            }
        }
        
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
    
    public function calculaSaldoAnterior(): void {
        if($this->aberto() === false){
            throw new FailException('Não é possível calcular os saldo anterior para período fechado.');
        }
        
        $anterior = new PeriodoRecord(Periodo::anterior($this->periodo));
        $saldoAnterior = $anterior->saldoAcumulado();
        
        $this->data['saldos']['anterior'] = $saldoAnterior;
        
        $this->salvar();
    }
    
    public function calculaSaldoPeriodo(): void {
        if($this->aberto() === false){
            throw new FailException('Não é possível calcular os saldo do período para período fechado.');
        }
        
        $receitaTotal = $this->receitaPrevistaTotal();
        $despesaTotal = $this->despesaPrevistaTotal();
        
        $saldoPeriodo = $receitaTotal - $despesaTotal;
        
        $this->data['saldos']['periodo'] = $saldoPeriodo;
        
        $this->salvar();
    }
    
    public function calculaSaldoAcumulado(): void {
        if($this->aberto() === false){
            throw new FailException('Não é possível calcular os saldo acumulado para período fechado.');
        }
        
        $saldoPeriodo = $this->saldoPeriodo();
        $saldoAnterior = $this->saldoAnterior();
        $saldoAcumulado = $saldoPeriodo + $saldoAnterior;
        
        $this->data['saldos']['acumulado'] = $saldoAcumulado;
        
        $this->salvar();
    }
    
    public function saldoAcumulado(bool $format = false) {
        $total = $this->data['saldos']['acumulado'];
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
    }
    
    public function saldoAnterior(bool $format = false) {
        $total = $this->data['saldos']['anterior'];
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
    }
    
    public function saldoPeriodo(bool $format = false) {
        $total = $this->data['saldos']['periodo'];
        
        if($format){
            return Number::format(round($total, 2));
        }
        
        return round($total, 2);
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
    
    public function reabrir(): void {
        if($this->aberto() === true){
            $atual = $this->format();
            throw new FailException("Não é possível reabrir [$atual] porque ele já está aberto.");
        }
        
        $posterior = new PeriodoRecord(Periodo::posterior($this->periodo));
        if($posterior->aberto() === false){
            $posterior = $posterior->format();
            $atual = $this->format();
            throw new FailException("Não é possível reabrir [$atual] porque [$posterior] ainda está fechado.");
        }
        
        $this->data['meta']['aberto'] = true;
        
        $this->salvar();
    }
    
    public function adicionaPrevisaoReceita(array $data): int {
        if($this->aberto() === false){
            throw new FailException('Não é possível adicionar previsão de receita em período já fechado.');
        }
        
        $index = array_key_last($this->data['receitas']);
        $index++;
        
        $this->data['receitas'][$index] = $data;
        
        $this->salvar();
        
        return $index;
    }
    
    public function receitas(): array {
        return $this->data['receitas'];
    }
    
    public function atualizaPrevisaoReceita(int $index, array $data): void {
        if($this->aberto() === false){
            throw new FailException('Não é possível modificar previsão de receita em período já fechado.');
        }
        
        if(key_exists($index, $this->data['receitas']) === false){
            throw new FailException("Índice de receita [$index] não encontrado.");
        }
        
        $this->data['receitas'][$index]['previsao'][] = $data;
        
        $this->salvar();
    }
    
    public function adicionaRecebimento(int $index, array $data): void {
        if($this->aberto() === false){
            throw new FailException('Não é possível adicionar recebimento de receita em período já fechado.');
        }
        
        $this->data['receitas'][$index]['recebimento'][] = $data;
        
        $this->salvar();
        
    }
}
