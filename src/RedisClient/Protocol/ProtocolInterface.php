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
namespace RedisClient\Protocol;

interface ProtocolInterface {

    /**
     * @param string[] $structure
     * @return mixed
     */
    public function send(array $structure);

    /**
     * @param array[] $structures
     * @return mixed
     */
    public function sendMulti(array $structures);

}
