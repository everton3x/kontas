<?php

namespace Kontas\App;

/**
 * Ambiente do programa
 *
 * @author Everton
 */
class KEnvironment extends \PTK\Console\Flow\Environment\EnvironmentAbstract {
    
    protected \PDO $dbh;
    protected string $dbDSN = 'sqlite:data/kontas.db';
    protected null|string $dbUser = null;
    protected null|string $dbPassword = null;


    public function __construct() {
        $this->prepareDb();
    }
    
    protected function prepareDb(): void {
        $this->dbh = new \PDO($this->dbDSN, $this->dbUser, $this->dbPassword);
        $this->dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }
    
    public function __get(string $member): mixed {
        if(property_exists($this, $member)){
            return $this->{$member};
        }
        
        throw new ResourceNotFoundException($member);
    }
    
}
