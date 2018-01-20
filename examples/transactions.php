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
 * Transactions
 * Note. Be careful in use transactions with Clusters. Try to use pipeline for it.
 */

namespace Examples;

require (dirname(__DIR__).'/vendor/autoload.php');
// or require (dirname(__DIR__).'/src/autoloader.php');

use RedisClient\Pipeline\Pipeline;
use RedisClient\Pipeline\PipelineInterface;
use RedisClient\RedisClient;

$Redis = new RedisClient();

// Example 1. Transactions

$Redis->watch(['foo', 'bar']);;
$Redis->multi();
$Redis->set('foo', 'bar');
$Redis->set('bar', 'foo');
$result = $Redis->exec();

var_dump($result);

//    array(2) {
//        [0]=> bool(true)
//        [1]=> bool(true)
//    }

// Example 2. True way to use transactions via Pipeline

$result = $Redis->pipeline(function(PipelineInterface $Pipeline) {
        /** @var Pipeline $Pipeline */
            $Pipeline->watch(['foo', 'bar']);;
            $Pipeline->multi();
            $Pipeline->set('foo', 'bar');
            $Pipeline->set('bar', 'foo');
            $Pipeline->exec();
        });

var_dump(end($result));

//    array(2) {
//        [0]=> bool(true)
//        [1]=> bool(true)
//    }
