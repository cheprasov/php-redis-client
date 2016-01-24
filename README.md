[![MIT license](http://img.shields.io/badge/license-MIT-brightgreen.svg)](http://opensource.org/licenses/MIT)

# RedisClient v1.0.0 for PHP >= 5.5

## About
RedisClient is a fast, fully-functional and user-friendly client for Redis, optimized for performance. RedisClient supports latests versions of Redis starting from 2.6

## Main features
- Support Redis versions from __2.6__ to __3.2-RC1__.
- Support TCP/IP and UNIX sockets.
- Support __PubSub__ and __monitor__ functionallity.
- Support __pipeline__.
- Connections to Redis are established lazily by the client upon the first command.
- Easy to use with IDE, client has PHPDocs for all supported versions.

## Usage

### Create a new instance of RedisClient
```php
<?php
require (dirname(__DIR__).'/src/autoloader.php');
// or require ('vendor/autoload.php');

use RedisClient\RedisClient;
use RedisClient\Client\Version\RedisClient2x6;

$Redis = new RedisClient([
    'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
    'timeout' => 2
]);

echo 'RedisClient: '. $Redis->getVersion() . PHP_EOL;
echo 'Redis: '. $Redis->info('Server')['redis_version'] . PHP_EOL;

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
