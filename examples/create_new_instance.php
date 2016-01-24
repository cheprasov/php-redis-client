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

require (dirname(__DIR__).'/vendor/autoload.php');
// or require (dirname(__DIR__).'/src/autoloader.php');

use RedisClient\RedisClient;
use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\ClientFactory;

// Example 1. Create new Instance with default config

$Redis = new RedisClient();
// By default, the client works with the latest stable version of Redis.
echo 'RedisClient: '. $Redis->getSupportedVersion() . PHP_EOL;
echo 'Redis: '. $Redis->info('Server')['redis_version'] . PHP_EOL;

// RedisClient: 3.0
// Redis: 3.0.3


// Example 2. Create new Instance with config
// By default, the client works with the latest stable version of Redis.

$Redis = new RedisClient([
    'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
    'timeout' => 2
]);

echo 'RedisClient: '. $Redis->getSupportedVersion() . PHP_EOL;
echo 'Redis: '. $Redis->info('Server')['redis_version'] . PHP_EOL;
// RedisClient: 3.0
// Redis: 3.0.3


// Example 3. Create new Instance for Redis version 2.6.x with config

$Redis = new RedisClient2x6([
    'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
    'timeout' => 2
]);

echo 'RedisClient: '. $Redis->getSupportedVersion() . PHP_EOL;
// RedisClient: 2.6

// Example 4. Create new Instance for Redis version 2.8.x with config via factory

$Redis = ClientFactory::create([
    'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
    'timeout' => 2,
    'version' => '2.8.24'
]);

echo 'RedisClient: '. $Redis->getSupportedVersion() . PHP_EOL;
// RedisClient: 2.8
