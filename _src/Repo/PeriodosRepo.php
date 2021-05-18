<?php

namespace Kontas\Repo;

use Kontas\Config\Config;
use Kontas\Recordset\PeriodoRecord;

/**
 * Description of Periodos
 *
 * @author Everton
 */
class PeriodosRepo {
    
    public function lista(): array {
        $dir = Config::periodosJsonDir();
        $items = scandir($dir, SCANDIR_SORT_DESCENDING);
        $periodos = [];
        foreach ($items as $node){
            if(is_file($dir.$node)){
                $periodos[basename($node, '.json')] = new PeriodoRecord(basename($node, '.json'));
            }
        }
        
        return $periodos;
    }
    
    public function listaAbertos(): array {
        $lista = $this->lista();
        $abertos = [];
        foreach ($lista as $periodo => $instancia){
            if($instancia->aberto()){
                $abertos[$periodo] = $instancia;
            }
        }
        
        return $abertos;
    }
    
    public function listaFechados(): array {
        $lista = $this->lista();
        $fechados = [];
        foreach ($lista as $periodo => $instancia){
            if($instancia->aberto() === false){
                $fechados[$periodo] = $instancia;
            }
        }
        
        return $fechados;
    }
    
    public function existeAgrupador(string $agrupador): bool {
        $lista = $this->lista();
        
        foreach ($lista as $periodo => $instancia){
            $receitas = $instancia->receitas();
            if(sizeof($receitas) > 0){
                foreach ($receitas as $index => $item){
                    if($item['agrupador'] === $agrupador){
                        return true;
                    }
                }
            }
            
            $despesas = $instancia->despesas();
            if(sizeof($despesas) > 0){
                foreach ($despesas as $index => $item){
                    if($item['agrupador'] === $agrupador){
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}
