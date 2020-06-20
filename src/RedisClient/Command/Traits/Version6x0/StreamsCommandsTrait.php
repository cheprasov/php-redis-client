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
namespace RedisClient\Command\Traits\Version6x0;

use RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait as StreamsCommandsTrait5x0;
/**
 * Streams Commands
 * @link https://redis.io/commands#stream
 * @link https://redis.io/topics/streams-intro
 */
trait StreamsCommandsTrait {

    use StreamsCommandsTrait5x0;

    /**
     * XINFO [CONSUMERS key groupname] [GROUPS key] [STREAM key [FULL]] [HELP]
     * Available since 5.0.0.
     * Time complexity: O(N) with N being the number of returned items for the subcommands CONSUMERS and GROUPS.
     * The STREAM subcommand is O(log N) with N being the number of items in the stream.
     * @link https://redis.io/commands/xinfo
     *
     * @param string[]|null $consumers [key, groupname]
     * @param string|null $groupsKey
     * @param string|null $streamKey
     * @param bool|null $streamFull
     * @param bool $help
     * @return array
     */
    public function xinfo($consumersKey = null, $consumersGroup = null, $groupsKey = null, $streamKey = null, $streamFull = null, $help = false) {
        $params = [];
        if (isset($consumersKey, $consumersGroup)) {
            $params[] = ['CONSUMERS', $consumersKey, $consumersGroup];
        }
        if (isset($groupsKey)) {
            $params[] = ['GROUPS', $groupsKey];
        }
        if (isset($streamKey)) {
            $stream = ['STREAM', $streamKey];
            if ($streamFull) {
                $stream[] = 'FULL';
            }
            $params[] = $stream;
        }
        if ($help) {
            $params[] = 'HELP';
        }
        return $this->returnCommand(['XINFO'], null, $params);
    }

}
