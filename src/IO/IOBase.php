<?php

namespace Kontas\IO;

/**
 * BAse para classes de i/o
 *
 * @author Everton
 */
class IOBase {
    
    protected \PTK\Console\Flow\Program\ProgramInterface $program;
    protected \League\CLImate\CLImate $console;
    protected \PDO $dbh;
    protected \PTK\Console\Flow\Environment\EnvironmentInterface $environment;
    
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program){
        $this->program = $program;
        $this->console = $program->console();
        $this->dbh = $program->dbh();
        $this->environment = $program->getEnvironment();
    }
}
