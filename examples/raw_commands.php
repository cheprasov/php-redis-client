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
 * RAW commands
 */

namespace Examples;

require (dirname(__DIR__).'/vendor/autoload.php');
// or require (dirname(__DIR__).'/src/autoloader.php');

use RedisClient\RedisClient;

$Redis = new RedisClient();

// Example 1. As string[] by <executeRaw>
// Every part of command must be a separate string
// <executeRaw> is better way to use raw commands than <executeRawString>

$Redis->executeRaw(['SET', 'foo', 'bar']);
echo 'result: '. $Redis->executeRaw(['GET', 'foo']) .PHP_EOL; // bar

// Example 2. As string by <executeRawString>
// It is better to use executeRaw from example 1.
$Redis->executeRawString('SET foo bar');
echo 'result: '. $Redis->executeRawString('GET foo') .PHP_EOL; // bar

// You can use quotes for keys and arguments
$Redis->executeRawString('SET "key with spaces" "or value with spaces"');
echo 'result: '. $Redis->executeRawString('GET "key with spaces"') .PHP_EOL; // or value with spaces
