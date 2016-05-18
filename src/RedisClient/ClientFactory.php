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
namespace RedisClient;

use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Client\Version\RedisClient3x0;
use RedisClient\Client\Version\RedisClient3x2;

class ClientFactory {

    protected static $versions = [
        '2.6' => RedisClient2x6::class,
        '2.8' => RedisClient2x8::class,
        '3.0' => RedisClient3x0::class,
        '3.2' => RedisClient3x2::class,
    ];

    /**
     * @param null|array $config
     * @return RedisClient2x6|RedisClient2x8|RedisClient3x0|RedisClient3x2|RedisClient
     */
    public static function create($config = null) {
        if (isset($config['version'])) {
            return self::createClientByVersion($config['version'], $config);
        }
        return new RedisClient($config);
    }

    /**
     * @param string $version
     * @param null|array $config
     * @return RedisClient2x6|RedisClient2x8|RedisClient3x0|RedisClient3x2
     */
    public static function createClientByVersion($version, $config = null) {
        list($major, $minor, $patch) = explode('.', $version.'.0.0');
        $ver = (int) $major .'.'. (int) $minor;

        $versions = array_keys(self::$versions);
        foreach ($versions as $v) {
            if ($v >= $ver) {
                $class = self::$versions[$v];
                break;
            }
        }
        if (empty($class)) {
            throw new \InvalidArgumentException(
                'RedisClient does not support Redis version '.$version.'. Please, use version '. end($versions)
            );
        }
        return new $class($config);
    }

}
