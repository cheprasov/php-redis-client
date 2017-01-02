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
 * Pipeline
 * Note. Be careful in use pipeline with Clusters.
 */

namespace Examples;

require (dirname(__DIR__).'/vendor/autoload.php');
// or require (dirname(__DIR__).'/src/autoloader.php');

use RedisClient\Pipeline\Pipeline;
use RedisClient\Pipeline\PipelineInterface;
use RedisClient\RedisClient;

$Redis = new RedisClient();

// Example 1. via new Pipeline

// Method 'pipeline' without params returns new Pipeline object;
$Pipeline = $Redis->pipeline();
// or $Pipeline = new Pipeline();
$Pipeline->set('foo', 'bar')->get('foo')->flushall();

var_dump($Redis->pipeline($Pipeline));

// result:
//    array(3) {
//        [0]=> bool(true)
//        [1]=> string(3) "bar"
//        [2]=> bool(true)
//    }


// Example 2. via Closure

// Method 'pipeline' without params returns new Pipeline object;
$result = $Redis->pipeline(
        function(PipelineInterface $Pipeline) {
            /** @var Pipeline $Pipeline */
            $Pipeline->set('foo', 'bar');
            $Pipeline->get('foo');
            $Pipeline->flushall();
        }
    );

var_dump($result);

// result:
//    array(3) {
//        [0]=> bool(true)
//        [1]=> string(3) "bar"
//        [2]=> bool(true)
//    }
