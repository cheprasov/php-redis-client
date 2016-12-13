[![MIT license](http://img.shields.io/badge/license-MIT-brightgreen.svg)](http://opensource.org/licenses/MIT)
[![Latest Stable Version](https://poser.pugx.org/cheprasov/php-redis-client/v/stable)](https://packagist.org/packages/cheprasov/php-redis-client)
[![Total Downloads](https://poser.pugx.org/cheprasov/php-redis-client/downloads)](https://packagist.org/packages/cheprasov/php-redis-client)
# RedisClient v1.6.0 for PHP >= 5.5

## About
RedisClient is a fast, fully-functional and user-friendly client for Redis, optimized for performance. RedisClient supports the latest versions of Redis starting from __2.6__ to __3.2__

## Main features
- Support Redis versions from __2.6.x__ to __4.0.x__.
- Support __TCP/IP__ and __UNIX__ sockets.
- Support __PubSub__ and __Monitor__ functionallity.
- Support __Pipeline__ and __Transactions__.
- Support __RAW__ commands as strings `"SET foo bar"` or as arrays `['SET', 'foo', 'bar']`.
- Connections to Redis are established lazily by the client upon the first command.
- Easy to use with IDE, client has PHPDocs for all supported versions.
- By default, the client works with the latest stable version of Redis (3.2.x).
- About **6.5-8.5% faster** than predis (based on this test: https://github.com/cheprasov/php-redis-client-vs-predis-test)
- Client was tested on the next versions of Redis: 2.6.17, 2.8.24, 3.0.7, 3.2.6, 4.0 RC2 (and older).
- Also, Client was tested on PHP 5.5, 5.6, 7.0, 7.1, HHVM.

## Usage

### Config

```php
$Redis = ClientFactory::create([
    // Optional. Default = '127.0.0.1:6379'. You can use 'unix:///tmp/redis.sock'
    'server' => '127.0.0.1:6379',

    // Optional. Default = 1
    'timeout' => 2,

    // Optional. Specify version to avoid some unexpected errors.
    'version' => '2.8.24',

    // Optional. Use it only if Redis server requires password (AUTH)
    'password' => 'some-password',

    // Optional. Use it, if you want to select not default db (db != 0) on connect
    'database' => 1,

    // Optional. Array with configs for RedisCluster support
    'cluster' => [
        'enabled' => false,

        // Oprional. Default = []. Map of cluster slots and servers
        // array(max_slot => server [, ...])
        // Examples for Cluster with 3 Nodes:
        'clusters' => [
            5460  => '127.0.0.1:7001', // slots from 0 to 5460
            10922 => '127.0.0.1:7002', // slots from 5461 to 10922
            16383 => '127.0.0.1:7003', // slots from 10923 to 16383
        ],

        // Optional. Default = false.
        // Use the param to update cluster slot map below on init RedisClient.
        // RedisClient will execute command CLUSTER SLOTS to get map.
        'init_on_start' => false,

        // Optional. Default = false.
        // If Redis returns error -MOVED then RedisClient will execute
        // command CLUSTER SLOTS to update cluster slot map
        'init_on_error' => true,
    ]
]);
```

### Create a new instance of RedisClient
```php
<?php
require (dirname(__DIR__).'/vendor/autoload.php');
// or require (dirname(__DIR__).'/src/autoloader.php');

use RedisClient\RedisClient;
use RedisClient\Client\Version\RedisClient2x6;

// Example 1. Create new Instance for Redis version 2.8.x with config via factory

$Redis = ClientFactory::create([
    'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
    'timeout' => 2,
    'version' => '2.8.24'
]);

echo 'RedisClient: '. $Redis->getSupportedVersion() . PHP_EOL;
// RedisClient: 2.8


// Example 2. Create new Instance for Redis version 2.6.x with config

$Redis = new RedisClient2x6([
    'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
    'timeout' => 2
]);

echo 'RedisClient: '. $Redis->getSupportedVersion() . PHP_EOL;
// RedisClient: 2.6


// Example 3. Create new instance of client without factory

$Redis = new RedisClient([
    'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
    'timeout' => 2
]);

echo 'RedisClient: '. $Redis->getSupportedVersion() . PHP_EOL;
echo 'Redis: '. $Redis->info('Server')['redis_version'] . PHP_EOL;

// By default, the client works with the latest stable version of Redis.
// RedisClient: 3.2
// Redis: 3.2.4


```
### Example
Please, see examples here: https://github.com/cheprasov/php-redis-client/tree/master/examples

- [Create new instance](https://github.com/cheprasov/php-redis-client/tree/master/examples/create_new_instance.php)
- [Using MONITOR](https://github.com/cheprasov/php-redis-client/tree/master/examples/monitor.php)
- [Publish and Subscribe](https://github.com/cheprasov/php-redis-client/tree/master/examples/pubsub.php)
- [Transactions](https://github.com/cheprasov/php-redis-client/tree/master/examples/transactions.php)
- [Pipeline](https://github.com/cheprasov/php-redis-client/tree/master/examples/pipeline.php)
- [Cluster support](https://github.com/cheprasov/php-redis-client/tree/master/examples/cluster.php)
- [RAW Commands](https://github.com/cheprasov/php-redis-client/tree/master/examples/raw_commands.php)

## Installation

### Composer

Download composer:

    wget -nc http://getcomposer.org/composer.phar

and add dependency to your project:

    php composer.phar require cheprasov/php-redis-client

## Running tests

1. Use Docker container with Redis for tests https://hub.docker.com/r/cheprasov/redis-for-tests/
2. To run tests type in console (do not forget run Docker):
    

    ./vendor/bin/phpunit


## Something doesn't work

Feel free to fork project, fix bugs and finally request for pull
