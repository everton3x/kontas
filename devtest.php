<?php

require_once 'vendor/autoload.php';
var_dump(setlocale(LC_ALL, 'ptb'));
$str = 'São José do Inhacorá 2021';
echo mb_detect_encoding($str);
echo iconv(mb_detect_encoding($str), 'ASCII//TRANSLIT', $str);