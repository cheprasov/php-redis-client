<?php
/**
 * This file is part of RedisClient.
 * git: https://github.com/cheprasov/php-redis-client
 *
 * (C) Alexander Cheprasov <acheprasov84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * PubSub
 */

namespace Examples;

require (dirname(__DIR__).'/vendor/autoload.php');
// or require (dirname(__DIR__).'/src/autoloader.php');

use RedisClient\RedisClient;

// Example 1. subscribe

$Redis = new RedisClient([
    'timeout' => 0 // for waiting answer infinitely
]);

$Redis->subscribe('channel.name', function($type, $channel, $message) {
    // This function will be called on subscribe and on message
    if ($type === 'subscribe') {
        // Note, if $type === 'subscribe'
        // then $channel = <channel-name>
        // and $message = <count of subsribers>
        echo 'Subscribed to channel <', $channel, '>', PHP_EOL;
    } elseif ($type === 'message') {
        echo 'Message <', $message, '> from channel <', $channel, '>', PHP_EOL;
        if ($message === 'quit') {
            // return <false> for unsubscribe and exit
            return false;
        }
    }
    // return <true> for to wait next message
    return true;
});

// Example 2. subscribe with timeout

$Redis = new RedisClient([
    'timeout' => 10 // for waiting answer for 10 seconds
]);

$Redis->subscribe('channel.name', function($type, $channel, $message) {
    // This function will be called on subscribe, on message and on timeout
    if (!isset($type)) {
        echo 'No any message for 10 second... exit'. PHP_EOL;
        // return <false> for unsubscribe and exit
        return false;
    }
    // This function will be called on subscribe and on message
    if ($type === 'subscribe') {
        // Note, if $type === 'subscribe'
        // then $channel = <channel-name>
        // and $message = <count of subsribers>
        echo 'Subscribed to channel <', $channel, '>', PHP_EOL;
    } elseif ($type === 'message') {
        echo 'Message <', $message, '> from channel <', $channel, '>', PHP_EOL;
    }
    // return <true> for to wait next message
    return true;
});

// Example 3. subscribe with external timeout

$Redis = new RedisClient([
    'timeout' => 0.25 // for waiting answer for 0.25 seconds
]);

$time = microtime(true);
$Redis->subscribe(['channel.name1', 'channel.name2'], function($type, $channel, $message) use ($time) {
    // This function will be called on subscribe, on message and on timeout (every 0.25 seconds)
    if (!isset($type)) {
        echo 'No any message for 0.25 seconds...'. PHP_EOL;

        // Will unsubscribe and exit after 10 seconds of timeout
        if (microtime(true) - $time > 10) {
            // return <false> for unsubscribe and exit
            return false;
        }
    }
    // This function will be called on subscribe and on message
    if ($type === 'subscribe') {
        // Note, if $type === 'subscribe'
        // then $channel = <channel-name>
        // and $message = <count of subsribers>
        echo 'Subscribed to channel <', $channel, '>', PHP_EOL;
    } elseif ($type === 'message') {
        echo 'Message <', $message, '> from channel <', $channel, '>', PHP_EOL;
    }
    // return <true> for to wait next message
    return true;
});


// Example 4. psubscribe

$Redis = new RedisClient([
    'timeout' => 0 // for waiting answer infinitely
]);

$Redis->psubscribe('channel.*', function($type, $pattern, $channel, $message) {
    // This function will be called on subscribe and on message
    if ($type === 'psubscribe') {
        // Note, if $type === 'psubscribe'
        // then $pattern = <channel-name>
        // and $channel = <count of subsribers>
        echo 'Subscribed to channel <', $pattern, '>', PHP_EOL;
    } elseif ($type === 'pmessage') {
        echo 'Message <', $message, '> from channel <', $channel, '> by pattern <', $pattern, '>', PHP_EOL;
        if ($message === 'quit') {
            // return <false> for unsubscribe and exit
            return false;
        }
    }
    // return <true> for to wait next message
    return true;
});
