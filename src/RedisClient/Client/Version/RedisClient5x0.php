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
namespace RedisClient\Client\Version;

use RedisClient\Client\AbstractRedisClient;
use RedisClient\Command\Traits\Version5x0\CommandsTrait;
use RedisClient\Pipeline\PipelineInterface;
use RedisClient\Pipeline\Version\Pipeline5x0;

class RedisClient5x0 extends AbstractRedisClient {
    use CommandsTrait;

    /**
     * @param \Closure|null $Pipeline
     * @return PipelineInterface
     */
    protected function createPipeline(\Closure $Pipeline = null) {
        return new Pipeline5x0($Pipeline);
    }

}
