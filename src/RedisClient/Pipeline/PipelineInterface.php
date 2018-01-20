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
namespace RedisClient\Pipeline;

interface PipelineInterface {

    /**
     * @param \Closure|null $Closure
     */
    public function __construct(\Closure $Closure = null);

    /**
     * @return string[]
     */
    public function getKeys();

    /**
     * @return array[]
     */
    public function getStructure();

    /**
     * @param array $responses
     * @return mixed
     */
    public function parseResponse($responses);

}
