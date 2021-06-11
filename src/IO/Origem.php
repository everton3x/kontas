<?php

namespace Kontas\IO;

class Origem extends IOBase {

    public function listar(int|null $ativo = null): void {
        $sql = 'SELECT * FROM origens';
        if ($aberto !== null) {
            $sql .= ' WHERE ativo = :ativo';
        }
        $sql .= ' ORDER BY ativo DESC, nome ASC;';

        $stmt = $this->dbh->prepare($sql);
        if ($aberto !== null) {
            $stmt->bindValue(':ativo', $ativo);
        }
        
        $stmt->execute();
        
        $list = $stmt->fetchAll();
        
        if($list === false){
            $this->console->info('Sem registros para mostrar.');
            return;
        }
        
        foreach ($list as $item){
            $nome = self::format($item['nome']);
            
            switch ($item['ativo']){
                case 0:
                    $ativo = 'I';
                    break;
                case 1:
                    $ativo = 'A';
                    break;
                default:
                    $ativo = '?';
                    break;
            }
            $this->console->inline($item['id'])->tab()->inline($ativo)->tab()->out($nome);
        }
    }
    
    public function detalhar(int $id): void {
        $entity = new \Kontas\Entity\Origem($this->program);
        $entity->load($id);
        switch ($entity->ativo()){
            case 1:
                $ativo = 'A';
                break;
            case 0:
                $ativo = 'I';
                break;
            default:
                $ativo = '?';
                break;
        }
        $this->program->console()->green()->inline('Id:')->tab()->out($entity->id());
        $this->program->console()->green()->inline('Nome:')->tab()->out($entity->nome());
        $this->program->console()->green()->inline('Ativo?')->tab()->out($ativo);
    }

}
