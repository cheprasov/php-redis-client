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

$Redis = new \RedisClient\RedisClient();

//var_dump($Redis->del(['a1', 'a2', 'a3']));

var_dump($Redis->hmset('hash', $hash));
//var_dump($Redis->hgetall('hash'));
//var_dump($Redis->hmget('hash', array_keys($hash2)));
var_dump($Redis->keys('k?y'));

//ar_dump($Redis->multi());

var_dump($Redis->hget('hash', 'a1'));
var_dump($Redis->hget('hash', 'a2'));
var_dump($Redis->hget('hash', 'a3'));


$res = $Redis->pipeline()->hget('hash', 'a1')->hget('hash', 'a1')->execPipeline();
var_dump($res);
$res = $Redis->pipeline()->hget('hash', 'a2')->multi()->hget('hash', 'a2')->hget('hash', 'a3')->exec()->execPipeline();
var_dump($res);


$Redis->hget('hash', 'a1');
$Redis->hget('hash', 'a2');
var_dump($Redis->executePipeline());
/*
$Redis->pipeline(function($Redis) {
    // ** @var \RedisClient\RedisClient $Redis * /
    $Redis->multi();
    //$Redis->multi();
    $Redis->hget('hash', 'a1');
    $Redis->hget('hash', 'a1');
    $Redis->hget('hash', 'a1');
    $Redis->hget('hash', 'a1');
    $Redis->keys('*');
    $Redis->exec();
});
*/

var_dump($Redis->executeCommand(new \RedisClient\Command\Command('HGETALL hash') ));
var_dump($Redis->executeCommand(new \RedisClient\Command\Command('HGETALL hash') ));
var_dump($Redis->executeCommand(new \RedisClient\Command\Command('COMMAND GETKEYS keys *') ));
var_dump($Redis->executeCommand(new \RedisClient\Command\Command('COMMAND COUNT') ));

$r = $Redis->commandInfo(['mget','mset']);
$r = $Redis->configGet('databases');
$r = $Redis->debugObject('hash');
$r = $Redis->time();

var_dump($r);


//$Redis->

//var_dump($Redis->);

