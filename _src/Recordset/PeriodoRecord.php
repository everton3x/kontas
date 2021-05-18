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
        $filename = Config::periodosJsonDir() . $this->periodo . '.json';
        $this->data = Json::read($filename);
    }

    protected function valida(): void {
        if (key_exists('periodo', $this->data) === false) {
            throw new FailException('Campo [periodo] está faltando.');
        }
        if (key_exists('receitas', $this->data) === false) {
            throw new FailException('Campo [receitas] está faltando.');
        }
        if (key_exists('despesas', $this->data) === false) {
            throw new FailException('Campo [despesas] está faltando.');
        }
        if (key_exists('saldos', $this->data) === false) {
            throw new FailException('Campo [saldos] está faltando.');
        }
        if (key_exists('meta', $this->data) === false) {
            throw new FailException('Campo [meta] está faltando.');
        }

        try {
            $data = date_create_from_format('Y-m', $this->data['periodo']);
        } catch (\Exception $ex) {
            throw new FailException('Campo [periodo] tem valor inválido: ' . $this->data['periodo']);
        }

        if (sizeof($this->data['receitas']) > 0) {
            $this->validaReceita();
        }

        if (sizeof($this->data['despesas']) > 0) {
            $this->validaDespesa();
        }

        $this->validaSaldos();
        
        $this->validaMeta();
    }

    protected function validaSaldos(): void {
        if (key_exists('periodo', $this->data['saldos']) === false) {
            throw new FailException('Campo [saldos.período] está faltando.');
        }
        if (key_exists('anterior', $this->data['saldos']) === false) {
            throw new FailException('Campo [saldos.anterior] está faltando.');
        }
        if (key_exists('acumulado', $this->data['saldos']) === false) {
            throw new FailException('Campo [saldos.acumulado] está faltando.');
        }

        if (is_numeric($this->data['saldos']['periodo']) === false) {
            throw new FailException('Campo [saldos.periodo] tem valor não numérico.');
        }
        if (is_numeric($this->data['saldos']['anterior']) === false) {
            throw new FailException('Campo [saldos.anterior] tem valor não numérico.');
        }
        if (is_numeric($this->data['saldos']['acumulado']) === false) {
            throw new FailException('Campo [saldos.acumulado] tem valor não numérico.');
        }
    }
    
    protected function validaMeta(): void {
        if (key_exists('aberto', $this->data['meta']) === false) {
            throw new FailException('Campo [meta.abert] está faltando.');
        }
        if (is_bool($this->data['meta']['aberto']) === false) {
            throw new FailException('Campo [meta.aberto] tem valor não booleano.');
        }
    }

    protected function validaDespesa(): void {
        foreach ($this->data['despesas'] as $index => $item){
            
            if (key_exists('descricao', $item) === false) {
                throw new FailException('Campo [despesas.descricao] está faltando: ' . $index);
            }
            if (key_exists('aplicacao', $item) === false) {
                throw new FailException('Campo [despesas.aplciacao] está faltando: ' . $index);
            }
            if (key_exists('projeto', $item) === false) {
                throw new FailException('Campo [despesas.projeto] está faltando: ' . $index);
            }
            if (key_exists('agrupador', $item) === false) {
                throw new FailException('Campo [despesas.agrupador] está faltando: ' . $index);
            }
            if (key_exists('parcela', $item) === false) {
                throw new FailException('Campo [despesas.parcela] está faltando: ' . $index);
            }
            if (key_exists('totalParcelas', $item) === false) {
                throw new FailException('Campo [despesas.totalParcelas] está faltando: ' . $index);
            }
            if (key_exists('previsao', $item) === false) {
                throw new FailException('Campo [despesas.previsao] está faltando: ' . $index);
            }
            if (key_exists('gasto', $item) === false) {
                throw new FailException('Campo [despesas.gasto] está faltando: ' . $index);
            }
            
            if (mb_strlen($item['descricao']) === 0) {
                throw new FailException('Campo [despesas.descricao] está vazio: ' . $index);
            }
            if (mb_strlen($item['aplicacao']) === 0) {
                throw new FailException('Campo [despesas.aplicacao] está vazio: ' . $index);
            }
            if (mb_strlen($item['projeto']) === 0) {
                throw new FailException('Campo [despesas.projeto] está vazio: ' . $index);
            }
            
            if (sizeof($item['previsao']) > 0) {
                foreach ($item['previsao'] as $subindex => $subitem) {
                    if (key_exists('valor', $subitem) === false) {
                        throw new FailException('Campo [despesas.previsao.valor] está faltando: ' . $subindex);
                    }
                    if (key_exists('data', $subitem) === false) {
                        throw new FailException('Campo [despesas.previsao.data] está faltando: ' . $subindex);
                    }
                    if (key_exists('observacao', $subitem) === false) {
                        throw new FailException('Campo [despesas.previsao.observacao] está faltando: ' . $subindex);
                    }

                    if (is_numeric($subitem['valor']) === false) {
                        throw new FailException('Campo [despesas.previsao.valor] tem valor não numérico: ' . $subindex);
                    }

                    try {
                        $date = date_create_from_format('Y-m-d', $subitem['data']);
                    } catch (Exception $ex) {
                        throw new FailException('Campo [despesas.previsao.data] tem valor inválido: ' . $index);
                    }
                }
            }
            
        }
    }

    protected function validaReceita(): void {
        foreach ($this->data['receitas'] as $index => $item) {
            if (key_exists('descricao', $item) === false) {
                throw new FailException('Campo [receitas.descricao] está faltando: ' . $index);
            }
            if (key_exists('origem', $item) === false) {
                throw new FailException('Campo [receitas.origem] está faltando: ' . $index);
            }
            if (key_exists('devedor', $item) === false) {
                throw new FailException('Campo [receitas.devedor] está faltando: ' . $index);
            }
            if (key_exists('cc', $item) === false) {
                throw new FailException('Campo [receitas.cc] está faltando: ' . $index);
            }
            if (key_exists('vencimento', $item) === false) {
                throw new FailException('Campo [receitas.vencimento] está faltando: ' . $index);
            }
            if (key_exists('agrupador', $item) === false) {
                throw new FailException('Campo [receitas.agrupador] está faltando: ' . $index);
            }
            if (key_exists('parcela', $item) === false) {
                throw new FailException('Campo [receitas.parcela] está faltando: ' . $index);
            }
            if (key_exists('totalParcelas', $item) === false) {
                throw new FailException('Campo [receitas.totalParcelas] está faltando: ' . $index);
            }
            if (key_exists('previsao', $item) === false) {
                throw new FailException('Campo [receitas.previsao] está faltando: ' . $index);
            }
            if (key_exists('recebimento', $item) === false) {
                throw new FailException('Campo [receitas.recebimento] está faltando: ' . $index);
            }

            if (mb_strlen($item['descricao']) === 0) {
                throw new FailException('Campo [receitas.descricao] está vazio: ' . $index);
            }
            if (mb_strlen($item['origem']) === 0) {
                throw new FailException('Campo [receitas.origem] está vazio: ' . $index);
            }
            if (mb_strlen($item['cc']) === 0) {
                throw new FailException('Campo [receitas.cc] está vazio: ' . $index);
            }

            try {
                $date = date_create_from_format('Y-m-d', $item['vencimento']);
            } catch (Exception $ex) {
                throw new FailException('Campo [receitas.vencimento] tem valor inválido: ' . $index);
            }

            if (sizeof($item['previsao']) > 0) {
                foreach ($item['previsao'] as $subindex => $subitem) {
                    if (key_exists('valor', $subitem) === false) {
                        throw new FailException('Campo [receitas.previsao.valor] está faltando: ' . $subindex);
                    }
                    if (key_exists('data', $subitem) === false) {
                        throw new FailException('Campo [receitas.previsao.data] está faltando: ' . $subindex);
                    }
                    if (key_exists('observacao', $subitem) === false) {
                        throw new FailException('Campo [receitas.previsao.observacao] está faltando: ' . $subindex);
                    }

                    if (is_numeric($subitem['valor']) === false) {
                        throw new FailException('Campo [receitas.previsao.valor] tem valor não numérico: ' . $subindex);
                    }

                    try {
                        $date = date_create_from_format('Y-m-d', $subitem['data']);
                    } catch (Exception $ex) {
                        throw new FailException('Campo [receitas.previsao.data] tem valor inválido: ' . $index);
                    }
                }
            }

            if (sizeof($item['recebimento']) > 0) {
                foreach ($item['recebimento'] as $subindex => $subitem) {
                    if (key_exists('valor', $subitem) === false) {
                        throw new FailException('Campo [receitas.recebimento.valor] está faltando: ' . $subindex);
                    }
                    if (key_exists('data', $subitem) === false) {
                        throw new FailException('Campo [receitas.recebimento.data] está faltando: ' . $subindex);
                    }
                    if (key_exists('observacao', $subitem) === false) {
                        throw new FailException('Campo [receitas.recebimento.observacao] está faltando: ' . $subindex);
                    }

                    if (is_numeric($subitem['valor']) === false) {
                        throw new FailException('Campo [receitas.recebimento.valor] tem valor não numérico: ' . $subindex);
                    }

                    try {
                        $date = date_create_from_format('Y-m-d', $subitem['data']);
                    } catch (Exception $ex) {
                        throw new FailException('Campo [receitas.recebimento.data] tem valor inválido: ' . $index);
                    }
                }
            }
        }
    }

    protected function salvar(): void {
        $this->valida();
        $filename = Config::periodosJsonDir() . $this->periodo . '.json';
        Json::write($this->data, $filename);
    }

    public function periodo(): string {
        return $this->periodo;
    }

    public function format(): string {
        return Periodo::format($this->periodo);
    }

    public function receitaPrevistaTotal(bool $format = false) {
        $total = 0.0;

        foreach ($this->data['receitas'] as $item) {
            foreach ($item['previsao'] as $subitem) {
                $total += $subitem['valor'];
            }
        }

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function despesaPrevistaTotal(bool $format = false) {
        $total = 0.0;

        //@todo
        $total = rand(0, 10000);

//        foreach ($this->data['despesas'] as $item){
//            foreach ($item['previsao'] as $subitem){
//                $total += $subitem['valor'];
//            }
//        }

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function despesaGastaTotal(bool $format = false) {
        $total = 0.0;

        //@todo
        $total = rand(0, 10000);

//        foreach ($this->data['despesas'] as $item){
//            foreach ($item['previsao'] as $subitem){
//                $total += $subitem['valor'];
//            }
//        }

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function despesaPagaTotal(bool $format = false) {
        $total = 0.0;

        //@todo
        $total = rand(0, 10000);

//        foreach ($this->data['despesas'] as $item){
//            foreach ($item['previsao'] as $subitem){
//                $total += $subitem['valor'];
//            }
//        }

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function despesaAGastarTotal(bool $format = false) {
        $total = $this->despesaPrevistaTotal() - $this->despesaGastaTotal();

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function despesaAPagarTotal(bool $format = false) {
        $total = $this->despesaGastaTotal() - $this->despesaPagaTotal();

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function receitaRecebidaTotal(bool $format = false) {
        //@todo
        $total = 0.0;

        foreach ($this->data['receitas'] as $item) {
            foreach ($item['recebimento'] as $subitem) {
                $total += $subitem['valor'];
            }
        }

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function receitaAReceberTotal(bool $format = false) {
        $total = $this->receitaPrevistaTotal() - $this->receitaRecebidaTotal();

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function fechar(): void {

        if ($this->aberto() === false) {
            $atual = $this->format();
            throw new FailException("Não é possível fechar [$atual] porque ele já está fechado.");
        }

        $anterior = new PeriodoRecord(Periodo::anterior($this->periodo));
        if ($anterior->aberto()) {
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
        if ($this->aberto() === false) {
            throw new FailException('Não é possível calcular os saldo anterior para período fechado.');
        }

        $anterior = new PeriodoRecord(Periodo::anterior($this->periodo));
        $saldoAnterior = $anterior->saldoAcumulado();

        $this->data['saldos']['anterior'] = $saldoAnterior;

        $this->salvar();
    }

    public function calculaSaldoPeriodo(): void {
        if ($this->aberto() === false) {
            throw new FailException('Não é possível calcular os saldo do período para período fechado.');
        }

        $receitaTotal = $this->receitaPrevistaTotal();
        $despesaTotal = $this->despesaPrevistaTotal();

        $saldoPeriodo = $receitaTotal - $despesaTotal;

        $this->data['saldos']['periodo'] = $saldoPeriodo;

        $this->salvar();
    }

    public function calculaSaldoAcumulado(): void {
        if ($this->aberto() === false) {
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

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function saldoAnterior(bool $format = false) {
        $total = $this->data['saldos']['anterior'];

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function saldoPeriodo(bool $format = false) {
        $total = $this->data['saldos']['periodo'];

        if ($format) {
            return Number::format(round($total, 2));
        }

        return round($total, 2);
    }

    public function aberto(): bool {
        switch ($this->data['meta']['aberto']) {
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
        if ($this->aberto() === true) {
            $atual = $this->format();
            throw new FailException("Não é possível reabrir [$atual] porque ele já está aberto.");
        }

        $posterior = new PeriodoRecord(Periodo::posterior($this->periodo));
        if ($posterior->aberto() === false) {
            $posterior = $posterior->format();
            $atual = $this->format();
            throw new FailException("Não é possível reabrir [$atual] porque [$posterior] ainda está fechado.");
        }

        $this->data['meta']['aberto'] = true;

        $this->salvar();
    }

    public function adicionaPrevisaoReceita(array $data): int {
        if ($this->aberto() === false) {
            throw new FailException('Não é possível adicionar previsão de receita em período já fechado.');
        }

        $index = array_key_last($this->data['receitas']);
        $index++;

        $this->data['receitas'][$index] = $data;

        $this->salvar();

        return $index;
    }
    
    public function adicionaPrevisaoDespesa(array $data): int {
        if ($this->aberto() === false) {
            throw new FailException('Não é possível adicionar previsão de despesa em período já fechado.');
        }

        $index = array_key_last($this->data['despesas']);
        $index++;

        $this->data['despesas'][$index] = $data;

        $this->salvar();

        return $index;
    }

    public function receitas(): array {
        return $this->data['receitas'];
    }
    
    public function despesas(): array {
        return $this->data['despesas'];
    }

    public function atualizaPrevisaoReceita(int $index, array $data): void {
        if ($this->aberto() === false) {
            throw new FailException('Não é possível modificar previsão de receita em período já fechado.');
        }

        if (key_exists($index, $this->data['receitas']) === false) {
            throw new FailException("Índice de receita [$index] não encontrado.");
        }

        $this->data['receitas'][$index]['previsao'][] = $data;

        $this->salvar();
    }

    public function adicionaRecebimento(int $index, array $data): void {
        if ($this->aberto() === false) {
            throw new FailException('Não é possível adicionar recebimento de receita em período já fechado.');
        }

        $this->data['receitas'][$index]['recebimento'][] = $data;

        $this->salvar();
    }

}
