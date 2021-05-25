<?php

namespace kontas\util;

/**
 * Description of template
 *
 * @author Everton
 */
class template {

    public const KTPL_DIR = 'templates/';
    public const KHTML_DIR = 'docs/';

    public static function engine(string $templates): \Twig\Environment {
        $loader = new \Twig\Loader\FilesystemLoader($templates);
        return new \Twig\Environment($loader);
    }

    public static function write(string $tpl, array $data = []): void {
        $engine = self::engine(self::KTPL_DIR);

        $template = $engine->load("$tpl.twig");
        $html = $template->render($data);
//        print_r($html);
        $filename = self::KHTML_DIR.$tpl.".html";
        file_put_contents($filename, $html);
    }

}
