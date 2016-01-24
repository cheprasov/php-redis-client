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
 * Monitor
 */

namespace Examples;

require (dirname(__DIR__).'/vendor/autoload.php');
// or require (dirname(__DIR__).'/src/autoloader.php');

use RedisClient\RedisClient;

// Example 1. monitor with timeout

$Redis = new RedisClient([
    'timeout' => 10 // for waiting answer for 10 seconds
]);

$Redis->monitor(function($message) {
    // This function will be called on message and on timeout
    if (!isset($message)) {
        echo 'No any message for 10 second... exit'. PHP_EOL;
        // return <false> for stop monitoring and exit
        return false;
    }

    echo $message, PHP_EOL;
    // return <true> for to wait next message
    return true;
});

// NOTE! You can not use $Redis after monitor,
// because connection with redis will be closed,
// it is correct way to stop monitor.
