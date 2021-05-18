<?php

require_once 'vendor/autoload.php';

$climate = new \League\CLImate\CLImate();

try{
    $climate->info('Lista as origens da receita...');
        
    $show = kontas\io\generic::choiceStatusOrAll();
    
    switch ($show){
        case 1:
            $list = \kontas\ds\origem::listActive();
            echo 'Ativos', PHP_EOL;
            break;
        case 0:
            $list = \kontas\ds\origem::listInactive();
            echo 'Inativos', PHP_EOL;
            break;
        case -1:
            echo 'Todos', PHP_EOL;
            $list = \kontas\ds\origem::listAll();
            break;
    }
    
    foreach ($list as $item){
        $climate->bold()->green()->out($item['nome']);
        $climate->tab()->inline('Descrição:')->tab()->out($item['descricao']);
        $climate->tab()->inline('Ativo:')->tab(2)->out($item['ativo']);
    }
    
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->yellow()->out($ex->getTraceAsString());
}