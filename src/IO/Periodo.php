<?php

namespace Kontas\IO;

class Periodo extends IOBase {

    public function listar(int|null $aberto = null): void {
        $sql = 'SELECT * FROM periodos';
        if ($aberto !== null) {
            $sql .= ' WHERE aberto = :aberto';
        }
        $sql .= ' ORDER BY periodo ASC;';

        $stmt = $this->dbh->prepare($sql);
        if ($aberto !== null) {
            $stmt->bindValue(':aberto', $aberto);
        }
        
        $stmt->execute();
        
        $list = $stmt->fetchAll();
        
        if($list === false){
            $this->console->info('Sem registros para mostrar.');
            return;
        }
        
        foreach ($list as $item){
            $periodo = self::format($item['periodo']);
            
            switch ($item['aberto']){
                case 0:
                    $aberto = 'F';
                    break;
                case 1:
                    $aberto = 'A';
                    break;
                default:
                    $aberto = '?';
                    break;
            }
            $this->console->inline($periodo)->tab()->out($aberto);
        }
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return string mm/aaaa
     */
    public static function format(string $periodo): string {
        $dt = \DateTime::createFromFormat('Y-m', $periodo);
        return $dt->format('m/Y');
    }

}
