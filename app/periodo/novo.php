<?php

use Kontas\Comando;
use Kontas\Config;
use Kontas\Periodo;
use League\CLImate\CLImate;

require_once 'vendor/autoload.php';

$climate = new CLImate();

$climate->description('Cria uma nova competência.');

try {
    $climate->arguments->add([
        'periodo' => [
            'prefix' => 'p',
            'longPrefix' => 'periodo',
            'description' => 'Período de competência no formato AAAAMM',
            'required' => true,
            'castTo' => 'string'
        ],
        'copiar' => [
            'prefix' => 'c',
            'longPrefix' => 'copiar',
            'description' => 'Período de competência no formato AAAAMM para ser copiado. A cópia somente será feita nas receitas e despesas sem agrupador.',
            'required' => false,
            'castTo' => 'string'
        ],
    ]);

    Comando::parseArgs($climate);
    
    $periodo = $climate->arguments->get('periodo');
    
    if(Periodo::periodoExiste($periodo) === true){
        $competencia = Periodo::formataParaCompetencia($periodo);
        throw new Exception("Período $competencia já existe.");
    }
    
    if(Periodo::periodoExiste(Periodo::periodoAnterior($periodo)) === false){
        $competencia = Periodo::formataParaCompetencia(Periodo::periodoAnterior($periodo));
        throw new Exception("Período anterior $competencia não existe.");
    }
    
    if($climate->arguments->defined('copiar')){
        throw new Exception('Copiar período ainda não implementado');
    }
    
    $dest = Config::DATA_DIR.$periodo.'.json';
    $copy = copy('periodo.json', $dest);
    if($copy === false){
        throw new Exception("Falha ao copiar período $dest");
    }
    
    $competencia = Periodo::formataParaCompetencia(Periodo::periodoAnterior($periodo));
    $climate->info("Período $competencia criado em $dest.");
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->whisper($ex->getTraceAsString());
    exit(126);
}