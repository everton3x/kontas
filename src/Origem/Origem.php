<?php

namespace Kontas\Origem;

/**
 * Description of Origem
 *
 * @author Everton
 */
class Origem {
    
    protected string $filename = 'data/auxiliar/origem.json';
    
    protected array $data;
    
    public function __construct() {
        $this->data = \Kontas\Json\Json::read($this->filename);
    }
    
    public function adiciona(string $nome, $descricao): int {
        if($this->existe($nome)){
            throw new \Exception("Origem já existe: $nome");
        }
        
        $index = array_key_last($this->data);
        $index++;
        
        $this->data[$index] = [
            'nome' => $nome,
            'descricao' => $descricao,
            'ativo' => true
        ];
        
        \Kontas\Json\Json::write($this->data, $this->filename);
        return $index;
    }
    
    public function consulta($index): array {
        if(key_exists($index, $this->data) === false){
            throw new Exception("Índice não encontrado: $index");
        }
        
        return $this->data[$index];
    }
    
    public function existe($nome): bool {
        foreach ($this->data as $index => $item){
            if(mb_strtolower($item['nome']) === mb_strtolower($nome)){
                return true;
            }
        }
        
        return false;
    }
    
    public function valida(array $data): void {
        foreach ($data as $index => $item){
            if(key_exists('nome', $item) === false){
                throw new \Exception("Campo [nome] faltando na linha [$index]");
            }
            if(key_exists('descricao', $item) === false){
                throw new \Exception("Campo [descricao] faltando na linha [$index]");
            }
            if(key_exists('ativo', $item) === false){
                throw new \Exception("Campo [ativo] faltando na linha [$index]");
            }
            
            if(mb_strlen($item['nome']) === 0){
                throw new \Exception("Campo [nome] com valor faltando na linha [$index]");
            }
            
            if(gettype($item['ativo']) !== 'bool'){
                throw new \Exception("Campo [ativo] com valor não booleano na linha [$index]");
            }
        }
    }
}
