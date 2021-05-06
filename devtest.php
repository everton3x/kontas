<?php

use Kontas\Comando;
use Kontas\Origem;
use League\CLImate\CLImate;

require_once 'vendor/autoload.php';

$climate = new CLImate();

$climate->description('teste');

try {
//    $climate->arguments->add([
//        'index' => [
//            'prefix' => 'i',
//            'longPrefix' => 'index',
//            'description' => 'Índice da origem da receita',
//            'required' => true,
//            'castTo' => 'int'
//        ]
//    ]);
//    Comando::parseArgs($climate);
    /////
    $input = $climate->input('>>>');
    $input->multiLine();
//    $input->defaultTo("isto é um valor padrão\n\raperte control z e enter para aplicar\n\rou quando terminar de digitar");
//    $input = $climate->password('Senha');

//    $options = ['Ice Cream', 'Mixtape', 'Teddy Bear', 'Pizza', 'Puppies'];
//    $input = $climate->checkboxes('Please send me all of the following:', $options);

    $response = $input->prompt();
    print_r($response);
//    $climate->clear();
    
} catch (Exception $ex) {
    $climate->error($ex->getMessage());
    $climate->whisper($ex->getTraceAsString());
    exit(126);
}
