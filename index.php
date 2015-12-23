<?php

spl_autoload_register(function($class) {
    $class = str_replace('\\', '/', $class);
    $path = './src/';
    if (is_file($file = $path . $class . '.php')) {
        return include $file;
    }
});

$hash = [
    'a1' => '111',
    'a2' => '222',
    'a3' => '333',
    'a4' => '444',
    'a5' => '555',
];

$hash2 = [
    'a1' => '111',
    'a2' => '222',
    'a3' => '333',
    'a7' => '333',
    'a4' => '444',
    'a5' => '555',
];


$Redis = new \RedisClient\RedisClient([
    'server' => 'tcp://127.0.0.1:6379'
]);

//var_dump($Redis->del(['a1', 'a2', 'a3']));

var_dump($Redis->hmset('hash', $hash));
var_dump($Redis->hgetall('hash'));
var_dump($Redis->hmget('hash', array_keys($hash2)));
var_dump($Redis->time());

var_dump($Redis->flushall());


//$Redis->

//var_dump($Redis->);

