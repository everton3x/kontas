<?php

namespace Kontas\Json;

use Ergebnis\Json\Printer\Printer;

/**
 * Manipula os arquivos Json
 *
 * @author Everton
 */
class Json {

    public static function read(string $filename): array {
        if (file_exists($filename) === false) {
            throw new Exception("Arquivo não encontrado: $filename");
        }

        $json = file_get_contents($filename);
        if ($json === false) {
            throw new Exception("Falha ao tentar ler o conteúdo: $filename");
        }

        $data = json_decode($json, true);
        if ($data === false) {
            throw new Exception("Falha ao converter $filename para JSON: " . json_last_error_msg());
        }

        return $data;
    }

    public static function write(array $data, string $filename): void {

        $json = json_encode($data);
        if ($json === false) {
            throw new Exception("Falha ao converter para JSON em $filename: " . json_last_error_msg());
        }

        $printer = new Printer();

//        $writed = file_put_contents($filename, $json);
        $writed = file_put_contents($filename, $printer->print($json));
        if ($writed === false) {
            throw new Exception("Não foi possível escrever JSON: $filename");
        }

        return;
    }

}
