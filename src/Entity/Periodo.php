<?php

namespace Kontas\Entity;

class Periodo {
    
    protected \PTK\Console\Flow\Program\ProgramInterface $program;
    protected \PDO $dbh;
    protected \DateTime $object;
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        $this->program = $program;
        $this->dbh = $program->dbh();
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return Periodo
     */
    public function setPeriodo(string $periodo): Periodo|bool {
        if($this->isMonthAndYearValid($periodo)){
            $this->object = \DateTime::createFromFormat('Y-m', $periodo);
            return $this;
        }else{
            $this->program->console()->error("Período inválido: $periodo");
            return false;
        }
        
        
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return bool
     */
    protected function isMonthAndYearValid(string $periodo): bool {
        $splitted = explode('-', $periodo);
        $year = $splitted[0];
        $month = $splitted[1];
        
        if($year <= 2000) {
            return false;
        }
        
        if($month < 1 || $month > 12){
            return false;
        }
        
        return true;
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return bool
     */
    protected function exists(string $periodo): bool {
        if(!$this->isMonthAndYearValid($periodo)){
            throw new InvalidPeriodo("Período inválido: $periodo");
        }
        $stmt = $this->dbh->prepare('SELECT * FROM periodos WHERE periodo LIKE :periodo');
        $stmt->bindValue(':periodo', $periodo);
        $stmt->execute();
        $rows = sizeof($stmt->fetchAll());
        
        if($rows === 0){
            return false;
        }elseif ($rows === 1) {
            return true;
        }else{
            throw new OutOfRangeException("Número de períodos encontrados para $periodo: $rows");
        }
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return Periodo
     */
    public function create(string $periodo): Periodo|bool {
        //é um mês/ano válido?
        if(!$this->isMonthAndYearValid($periodo)){
            $this->program->console()->error("Período inválido: $periodo");
            return false;
        }
        
        //ainda não existe?
        if($this->exists($periodo)){
            $this->program->console()->error("Período já existe: $periodo");
            return false;
        }
        
        //existe período anterior? Só pode criar um período se existir um anterior.
        $previous = $this->previous($periodo);
        if(!$this->exists($previous)){
            $this->program->console()->error("Período anterior não existe: $previous");
            return false;
        }
        //cria o período
        $stmt = $this->dbh->prepare('INSERT INTO periodos (periodo, aberto) VALUES (:periodo, :aberto);');
        $stmt->bindValue(':periodo', $periodo);
        $stmt->bindValue(':aberto', 1);
        if(!$stmt->execute()){
            $this->program->console()->error(
                    sprintf('Falha ao salvar %s: %s', $periodo, $this->dbh->errorInfo())
            );
            return false;
        }
        
        $this->setPeriodo($periodo);
        return $this;
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return string aaaa-mm
     * @throws InvalidPeriodo
     */
    public function previous(string $periodo): string {
        if(!$this->isMonthAndYearValid($periodo)){
            throw new InvalidPeriodo("Período inválido: $periodo");
        }
        
        $dt = \DateTime::createFromFormat('Y-m', $periodo);
        $interval = new \DateInterval('P1M');
        $dt->sub($interval);
        
        return $dt->format('Y-m');
    }
    
    public function get(): \DateTime {
        return $this->object;
    }
}
