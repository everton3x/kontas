<?php

namespace Kontas;

use Exception;

/**
 * Origem de receita
 *
 * @author Everton
 */
class Origem {

    public static function existe(string $nome): bool {
        $data = self::lista();
        foreach ($data as $key => $item) {
            if (strtolower($item['nome']) === strtolower($nome)) {
                return true;
            }
        }
        return false;
    }

    public static function adiciona(string $nome, string $descricao = ''): void {
        if (self::existe($nome)) {
            throw new Exception("$nome já existe.");
        }

        $data = self::lista();
        $data[] = [
            'nome' => $nome,
            'descricao' => $descricao,
            'ativo' => true
        ];
        
        self::salva($data);
    }

    public static function consulta(int $index): array {
        $data = self::lista();
        if(!key_exists($index, $data)){
            throw new Exception("Índice $index não encontrado.");
        }
        
        return $data[$index];
    }
    
    public static function altera(int $index, ?string $novoNome = null, ?string $novaDescricao = null): void {
        $data = self::lista();
        if(!key_exists($index, $data)){
            throw new Exception("Índice $index não encontrado.");
        }
        
        if($novoNome !== null){
            $data[$index]['nome'] = $novoNome;
        }
        
        if($novaDescricao!== null){
            $data[$index]['descricao'] = $novaDescricao;
        }
        
        self::salva($data);
    }

    public static function ativo(int $index, bool $ativo): void {
        $data = self::lista();
        if(!key_exists($index, $data)){
            throw new Exception("Índice $index não encontrado.");
        }
        
        $data[$index]['ativo'] = $ativo;
        
        self::salva($data);
    }

    public static function lista(): array {
        $filename = Config::DATA_DIR . 'origem.json';
        $json = file_get_contents($filename);
        if ($json === false) {
            throw new Exception("Não foi possível ler os dados de $filename.");
        }
        $data = json_decode($json, true);
        if ($data === false) {
            throw new Exception(json_last_error_msg());
        }

        return $data;
    }

    protected static function salva(array $data): void {
        $json = json_encode($data);
        if ($json === false) {
            throw new Exception(json_last_error_msg());
        }

        $filename = Config::DATA_DIR . 'origem.json';
        $write = file_put_contents($filename, $json);
        if ($write === false) {
            throw new Exception("Não foi possível salvar os dados em $filename.");
        }
    }
    
    public static function listaAtivos(): array {
        $data = self::lista();
        $result = [];
        foreach ($data as $index => $item){
            if($item['ativo'] === true){
                $result[$index] = $item;
            }
        }
        
        return $result;
    }
    
    public static function listaInativos(): array {
        $data = self::lista();
        $result = [];
        foreach ($data as $index => $item){
            if($item['ativo'] === false){
                $result[$index] = $item;
            }
        }
        
        return $result;
    }

}
