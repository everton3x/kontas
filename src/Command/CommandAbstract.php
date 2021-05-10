<?php

namespace Kontas\Command;

/**
 * Base para os comandos
 *
 * @author Everton
 */
abstract class CommandAbstract {
    
    protected \League\CLImate\CLImate $climate;


    abstract public function execute(): void;
    
    public function showUsage(): void {
        if($this->climate->arguments->get('help')){
            $this->climate->usage();
            exit();
        }
    }
    
    public function __construct(string $description, array $args = []) {
        $this->climate = new \League\CLImate\CLImate();
        $this->climate->description($description);
        
        if(key_exists('help', $args) === false){
            $args = array_merge($args, ['help' => [
                'prefix' => 'h',
                'longPrefix' => 'help',
                'description' => 'Exibe a ajuda do comando',
                'noValue' => true
            ]]);
        }
        
        if(sizeof($args) > 0){
            $this->climate->arguments->add($args);
        }
        
        $this->climate->arguments->parse();
        
        $this->showUsage();
    }
    
}
