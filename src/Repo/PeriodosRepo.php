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
}
