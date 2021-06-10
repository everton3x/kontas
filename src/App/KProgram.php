<?php

namespace Kontas\App;

/**
 * Programa principal
 *
 * @author Everton
 */
class KProgram extends \PTK\Console\Flow\Program\ProgramAbstract {
    
    public function __construct(\PTK\Console\Flow\Environment\EnvironmentInterface $environment) {
        parent::__construct($environment);
    }
    
    public function run(): void {
        $this->executeEntryPoint();
    }

    public function dbh(): \PDO {
        return $this->environment->dbh;
    }
    
    public function pause(string $msg = 'Pressione ENTER para continuar...'): void {
        $input = $this->console()->input($msg);
        $input->prompt();
    }
}
