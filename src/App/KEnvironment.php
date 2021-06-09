<?php

namespace Kontas\App;

/**
 * Ambiente do programa
 *
 * @author Everton
 */
class KEnvironment extends \PTK\Console\Flow\Environment\EnvironmentAbstract {
    
    protected \PDO $dbh;
    protected string $dbDSN = 'sqlite:data/kontas.sqlite';
    protected null|string $dbUser = null;
    protected null|string $dbPassword = null;


    public function __construct() {
        $this->prepareDb();
    }
    
    protected function prepareDb(): void {
        $this->dbh = new \PDO($this->dbDSN, $this->dbUser, $this->dbPassword);
    }
    
}
