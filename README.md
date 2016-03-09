[![MIT license](http://img.shields.io/badge/license-MIT-brightgreen.svg)](http://opensource.org/licenses/MIT)
[![Latest Stable Version](https://poser.pugx.org/cheprasov/php-redis-client/v/stable)](https://packagist.org/packages/cheprasov/php-redis-client)
[![Total Downloads](https://poser.pugx.org/cheprasov/php-redis-client/downloads)](https://packagist.org/packages/cheprasov/php-redis-client)
# RedisClient v1.2.1 for PHP >= 5.5

## About
RedisClient is a fast, fully-functional and user-friendly client for Redis, optimized for performance. RedisClient supports latest versions of Redis starting from __2.6__

## Main features
- Support Redis versions from __2.6__ to __3.2.0-RC3__.
- Support TCP/IP and UNIX sockets.
- Support __PubSub__ and __Monitor__ functionallity.
- Support __Pipeline__ and __Transactions__.
- Support RAW commands as strings `"SET foo bar"` or as arrays `['SET', 'foo', 'bar']`.
- Connections to Redis are established lazily by the client upon the first command.
- Easy to use with IDE, client has PHPDocs for all supported versions.
- By default, the client works with the latest stable version of Redis.

## Usage

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
// RedisClient: 3.0
// Redis: 3.0.3


```
### Example
Please, see examples here: https://github.com/cheprasov/php-redis-client/tree/master/examples


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
