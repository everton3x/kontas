<?php

namespace kontas\ds;

/**
 * Description of origem
 *
 * @author Everton
 */
class origem {
    
    protected const FILENAME = 'data/auxiliar/origem.json';
    
    public static function add(string $nome, string $descricao): int {
        if(self::exists($nome)){
            trigger_error("$nome já existe.", E_USER_ERROR);
        }
        
        if(mb_strlen($nome) === 0){
            trigger_error('$nome não pode ser vazio.', E_USER_ERROR);
        }
        
        $data = self::load();
        $data[] = [
            'nome' => $nome,
            'descricao' => $descricao,
            'ativo' => true
        ];
        
        if(self::save($data) === false){
            trigger_error("Falha ao salvar $nome", E_USER_ERROR);
        }
        
        return array_key_last($data);
    }
    
    public static function validate(array $data): bool {
        foreach ($data as $index => $item){
            $key = \kontas\util\check::testIfKeyExists(['nome', 'descricao', 'ativo'], $item);
            if($key !== ''){
                trigger_error("Chave $key faltando.", E_USER_ERROR);
            }
            
            $key = \kontas\util\check::testIfValueNotIsEmpty(['nome'], $item);
            if($key !== ''){
                trigger_error("Chave $key sem valor.", E_USER_ERROR);
            }
        }
        
        return true;
    }
    
    public static function changeStatus(bool $status): bool {
        
    }
    
    public static function exists(string $nome): bool {
        foreach (self::load() as $item){
            if($item['nome'] === $nome){
                return true;
            }
        }
        
        return false;
    }
    
    public static function load(): array {
        return \kontas\util\json::load(self::FILENAME);        
    }
    
    public static function save(array $data): bool {
        if(self::validate($data) === false){
            trigger_error('Estrutura de dados inválida.', E_USER_ERROR);
        }
        
        return \kontas\util\json::write(self::FILENAME, $data);
    }
    
    public static function listAll(): array {
        return self::load();
    }
    
    public static function listActive(): array {
        $list = [];
        foreach (self::listAll() as $item){
            switch ($item['ativo']){
                case true:
                case 'true':
                    $list[] = $item;
            }
        }
        return $list;
    }
    
    public static function listInactive(): array {
        $list = [];
        foreach (self::listAll() as $item){
            switch ($item['ativo']){
                case false:
                case 'false':
                    $list[] = $item;
            }
        }
        return $list;
    }
    
}
