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
            throw new \OutOfRangeException("Número de períodos encontrados para $periodo: $rows");
        }
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return Periodo
     */
    public function open(string $periodo): Periodo|bool {
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
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return bool
     * @throws InvalidPeriodo
     * @throws \OutOfRangeException
     */
    public function isOpen(string $periodo): bool {
        if(!$this->isMonthAndYearValid($periodo)){
            throw new InvalidPeriodo("Período inválido: $periodo");
        }
        $stmt = $this->dbh->prepare('SELECT * FROM periodos WHERE periodo LIKE :periodo');
        $stmt->bindValue(':periodo', $periodo);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        if (sizeof($rows) === 1) {
            if($rows[0]['aberto'] === "0"){
                return false;
            }elseif($rows[0]['aberto'] === "1"){
                return true;
            }else{
                throw new \OutOfRangeException("Valor inválido para o campo [aberto]: {$rows[0]['aberto']}.");
            }
        }else{
            throw new \OutOfRangeException("Número de períodos encontrados para $periodo.");
        }
    }
    
    /**
     * 
     * @param string $periodo aaaa-mm
     * @return Periodo|bool
     */
    public function close(string $periodo = ''): Periodo|bool {
        if($periodo === ''){
            $periodo = $this->object->format('Y-m');
        }
        
        //é um mês/ano válido?
        if(!$this->isMonthAndYearValid($periodo)){
            $this->program->console()->error("Período inválido: $periodo");
            return false;
        }
        
        //o período existe?
        if(!$this->exists($periodo)){
            $this->program->console()->error("Período não existe: $periodo");
            return false;
        }
        
        //o período está aberto?
        if(!$this->isOpen($periodo)){
            $this->program->console()->error("Período já está fechado: $periodo");
            return false;
        }
        
        //o período anterior está fechado? só posso fechar se o anterior estiver fechado.
        //não posso fechar se o anterior não estiver fechado por causa do cálculo dos resultados.
        //não tem sentido fechar um se o anterior ainda estiver aberto
        $previous = $this->previous($periodo);
        if($this->isOpen($previous)){
            $this->program->console()->error("Período anterior ainda está aberto: $previous");
            return false;
        }
        
        //fecha
        $stmt = $this->dbh->prepare('UPDATE periodos SET aberto = :aberto WHERE periodo LIKE :periodo');
        $stmt->bindValue(':periodo', $periodo);
        $stmt->bindValue(':aberto', 0);
        if(!$stmt->execute()){
            $this->program->console()->error(
                    sprintf('Falha ao fechar %s: %s', $periodo, $this->dbh->errorInfo())
            );
            return false;
        }
        
        return $this;
    }
    
    public function reopen(string $periodo = ''): Periodo|bool {
        if($periodo === ''){
            $periodo = $this->object->format('Y-m');
        }
        
        //é um mês/ano válido?
        if(!$this->isMonthAndYearValid($periodo)){
            $this->program->console()->error("Período inválido: $periodo");
            return false;
        }
        
        //o período existe?
        if(!$this->exists($periodo)){
            $this->program->console()->error("Período não existe: $periodo");
            return false;
        }
        
        //o período está fechado?
        if($this->isOpen($periodo)){
            $this->program->console()->error("Período já está aberto: $periodo");
            return false;
        }
        
        //reabre
        $stmt = $this->dbh->prepare('UPDATE periodos SET aberto = :aberto WHERE periodo LIKE :periodo');
        $stmt->bindValue(':periodo', $periodo);
        $stmt->bindValue(':aberto', 1);
        if(!$stmt->execute()){
            $this->program->console()->error(
                    sprintf('Falha ao reabrir %s: %s', $periodo, $this->dbh->errorInfo())
            );
            return false;
        }
        
        return $this;
    }
    
    /**
     * 
     * @param string|null $periodo aaaa-mm
     * @return string aaaa-mm-dd
     */
    public function getLastDay(string|null $periodo = null): string {
        if($periodo === null){
            $periodo = $this->object;
        }else{
            $periodo = \DateTime::createFromFormat('Y-m', $periodo);
        }
        
        $year = $periodo->format('Y');
        $month = $periodo->format('m');
        $day = $periodo->format('t');
        
        $periodo->setDate($year, $month, $day);
        
        return $periodo->format('Y-m-d');
    }
}
