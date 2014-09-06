<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use Functional as F;

$templates = array(
    'private' => file_get_contents(__DIR__.'/private-function.php.template'),
    'public' => file_get_contents(__DIR__.'/public-function.php.template'),
    'class' => file_get_contents(__DIR__.'/F.php.template'),
);

require_once __DIR__.'/get_function_infos.php';

$functionInfos = get_function_infos(
    __DIR__.'/../vendor/lstrojny/functional-php/src/Functional'
);

$functionDefs = F\map($functionInfos, function($info) use($templates){
    return strtr($templates[$info['%type%']], $info);
});

$classDef = strtr($templates['class'], array('%functions%' => join("\n\n", $functionDefs)));

file_put_contents(__DIR__.'/../src/Rfp/F.php', $classDef);