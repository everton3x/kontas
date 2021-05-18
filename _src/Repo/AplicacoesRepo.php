<?php

namespace Kontas\Repo;

use Kontas\Config\Config;
use Kontas\Exception\FailException;
use Kontas\Json\Json;

/**
 * Description of Aplicacoes
 *
 * @author Everton
 */
class AplicacoesRepo {
    protected array $data;

    public function __construct() {
        $this->read();
    }

    protected function read(): void {
        $this->data = Json::read(Config::aplicacaoJsonFile());
    }

    protected function validate(): void {
        foreach ($this->data as $index => $item){
            if(key_exists('nome', $item) === false){
                throw new FailException("Campo [nome] não existe no índice [$index].");
            }
            if(key_exists('descricao', $item) === false){
                throw new FailException("Campo [descricao] não existe no índice [$index].");
            }
            if(key_exists('ativo', $item) === false){
                throw new FailException("Campo [ativo] não existe no índice [$index].");
            }
            
            if(mb_strlen($item['nome']) === 0){
                throw new FailException("Campo [nome] não tem valor no índice [$index].");
            }
            
            if(is_bool($item['ativo']) === false){
                throw new FailException("Campo [ativo] não tem valor booleano no índice [$index].");
            }
        }
    }

    public function write(): void {
        $this->validate();
        Json::write($this->data, Config::aplicacaoJsonFile());
        $this->read();
    }

    public function add(string $nome, string $descricao, bool $ativo): int {
        if ($this->exist($nome)) {
            throw new FailException("$nome já existe.");
        }

        $index = array_key_last($this->data);
        $index++;

        $this->data[$index] = [
            'nome' => $nome,
            'descricao' => $descricao,
            'ativo' => $ativo
        ];

        $this->write();

        return $index;
    }

    public function exist(string $nome): bool {
        foreach ($this->data as $index => $item) {
            if (mb_strtolower($item['nome']) === mb_strtolower($nome)) {
                return true;
            }
        }
        return false;
    }

    public function record(int $index): array {
        $this->indexExist($index);
        return $this->data[$index];
    }

    protected function indexExist(int $index): void {
        if (key_exists($index, $this->data) === false) {
            throw new FailException("Índice $index não existe.");
        }
    }
    
    public function changeStatus(int $index, bool $status): void {
        $this->indexExist($index);
        $this->data[$index]['ativo'] = $status;
        $this->write();
    }
    
    public function list(): array {
        return $this->data;
    }
    
    public function listAtivos(): array {
        $result = [];
        
        foreach ($this->data as $index => $item){
            if($item['ativo'] === true){
                $result[$index] = $item;
            }
        }
        
        return $result;
    }
    
    public function listInativos(): array {
        $result = [];
        
        foreach ($this->data as $index => $item){
            if($item['ativo'] === false){
                $result[$index] = $item;
            }
        }
        
        return $result;
    }
}
