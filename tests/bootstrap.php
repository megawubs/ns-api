<?php

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv())->load(__DIR__ . "/../");

function write($object, $fileName)
{
    file_put_contents(__DIR__ . '/' . $fileName . '.txt', print_r($object, true));
}

