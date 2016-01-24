<?php
/**
 * This file is part of RedisClient.
 * git: https://github.com/cheprasov/php-redis-client
 *
 * (C) Alexander Cheprasov <cheprasov.84@ya.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Create a new instance of RedisClient
 */

namespace Examples;

require (dirname(__DIR__).'/src/autoloader.php');
// or require ('vendor/autoload.php');

use RedisClient\RedisClient;
use RedisClient\Client\Version\RedisClient2x6;

// Example 1. Create new Instance with default config

$Redis = new RedisClient();

echo 'RedisClient: '. $Redis->getVersion() . PHP_EOL;
echo 'Redis: '. $Redis->info('Server')['redis_version'] . PHP_EOL;

// result:
// RedisClient: 3.0
// Redis: 3.0.3


// Example 2. Create new Instance with config

$Redis = new RedisClient([
    'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
    'timeout' => 2
]);

echo 'RedisClient: '. $Redis->getVersion() . PHP_EOL;
echo 'Redis: '. $Redis->info('Server')['redis_version'] . PHP_EOL;

// result:
// RedisClient: 3.0
// Redis: 3.0.3


// Example 3. Create new Instance for Redis version 2.6.x with config

$Redis = new RedisClient2x6([
    'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
    'timeout' => 2
]);

echo 'RedisClient: '. $Redis->getVersion() . PHP_EOL;
echo 'Redis: '. $Redis->info('Server')['redis_version'] . PHP_EOL;

// result:
// RedisClient: 2.6
// Redis: 3.0.3
