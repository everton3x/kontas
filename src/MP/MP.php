<?php

namespace Kontas\MP;

use Exception;
use Kontas\Config\Config;
use Kontas\Json\Json;

/**
 *
 * @author Everton
 */
class MP {

    protected string $filename;
    protected array $data;

    public function __construct() {
        $this->filename = Config::getMPJsonFile();
        $this->data = Json::read($this->filename);
        if (sizeof($this->data) > 0) {
            $this->ordena();
        }
    }

    protected function ordena(): void {
        foreach ($this->data as $index => $item) {
            $nome[$index] = $item['nome'];
            $ativo[$index] = $item['ativo'];
        }
        array_multisort($nome, SORT_ASC, $this->data);
    }

    public function adiciona(string $nome, string $descricao, bool $autopagar): int {
        if ($this->existe($nome)) {
            throw new Exception("Meio de pagamento já existe: $nome");
        }

        $index = array_key_last($this->data);
        $index++;

        $this->data[$index] = [
            'nome' => $nome,
            'descricao' => $descricao,
            'autopagar' => $autopagar,
            'ativo' => true
        ];

        $this->salvar();
        return $index;
    }

    public function consulta(int $index): array {
        $this->indexExiste($index);

        return $this->data[$index];
    }

    public function existe($nome): bool {
        foreach ($this->data as $index => $item) {
            if (mb_strtolower($item['nome']) === mb_strtolower($nome)) {
                return true;
            }
        }

        return false;
    }

    public function valida(array $data): void {
        foreach ($data as $index => $item) {
            if (key_exists('nome', $item) === false) {
                throw new Exception("Campo [nome] faltando na linha [$index]");
            }
            if (key_exists('descricao', $item) === false) {
                throw new Exception("Campo [descricao] faltando na linha [$index]");
            }
            if (key_exists('autopagar', $item) === false) {
                throw new Exception("Campo [autopagar] faltando na linha [$index]");
            }
            if (key_exists('ativo', $item) === false) {
                throw new Exception("Campo [ativo] faltando na linha [$index]");
            }

            if (mb_strlen($item['nome']) === 0) {
                throw new Exception("Campo [nome] com valor faltando na linha [$index]");
            }

            if (gettype($item['ativo']) !== 'boolean') {
                throw new Exception("Campo [ativo] com valor não booleano na linha [$index]");
            }
            
            if (gettype($item['autopagar']) !== 'boolean') {
                throw new Exception("Campo [autopagar] com valor não booleano na linha [$index]");
            }
        }
    }

    public function lista(): array {
        return $this->data;
    }

    public function listaAtivos(): array {
        $result = [];

        foreach ($this->data as $index => $item) {
            if ($item['ativo'] === true) {
                $result[$index] = $item;
            }
        }

        return $result;
    }

    public function listaInativos(): array {
        $result = [];

        foreach ($this->data as $index => $item) {
            if ($item['ativo'] === false) {
                $result[$index] = $item;
            }
        }

        return $result;
    }

    protected function indexExiste(int $index): void {
        if (key_exists($index, $this->data) === false) {
            throw new Exception("Índice não encontrado: $index");
        }
    }

    public function alteraStatus(int $index, bool $ativo): void {
        $this->indexExiste($index);

        $this->data[$index]['ativo'] = $ativo;

        $this->salvar();
    }

    protected function salvar(): void {
        $this->valida($this->data);

        Json::write($this->data, $this->filename);
    }
    
    public function tabular(bool $todos = false): array {
        $lista = $this->listaAtivos();
        if($todos){
            $lista = $this->lista();
        }
        
        $tabular = [];
        foreach ($lista as $index => $item){
            $tabular[] = [
                'ID' => $index,
                'Nome' => $item['nome'],
                'Descrição' => $item['descricao'],
                'Ativo' => $item['ativo'],
                'Auto-pagamento' => $item['autopagar']
            ];
        }
        return $tabular;
    }

}
