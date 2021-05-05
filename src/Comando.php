<?php

namespace Kontas;

/**
 * Utilitários para comandos
 *
 * @author Everton
 */
class Comando {

    /**
     * Rotina de parse dos argumentos dos comandos
     * 
     * @param \League\CLImate\CLImate $climate
     * @return void
     */
    public static function parseArgs(\League\CLImate\CLImate $climate): void {
        try {
            $climate->arguments->parse();
        } catch (\Exception $ex) {
            $climate->error('Argumentos obrigatórios estão faltando.');
            $climate->usage();
            exit(128);
        }
        
    }

}
