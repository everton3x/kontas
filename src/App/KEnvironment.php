<?php

namespace Kontas\App;

/**
 * Ambiente do programa
 *
 * @author Everton
 */
class KEnvironment extends \PTK\Console\Flow\Environment\EnvironmentAbstract {
    
    protected string $dbDSN = 'sqlite:data/kontas.db';
    protected null|string $dbUser = null;
    protected null|string $dbPassword = null;


    public function __construct() {
        $this->prepareDb();
    }
    
    protected function prepareDb(): void {
        $this->resources['dbh'] = new \PDO($this->dbDSN, $this->dbUser, $this->dbPassword);
        $this->resources['dbh']->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }
}
