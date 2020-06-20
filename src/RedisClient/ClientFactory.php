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
namespace RedisClient;

use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Client\Version\RedisClient3x0;
use RedisClient\Client\Version\RedisClient3x2;
use RedisClient\Client\Version\RedisClient4x0;
use RedisClient\Client\Version\RedisClient5x0;
use RedisClient\Client\Version\RedisClient6x0;
use RedisClient\Exception\ErrorException;
use RedisClient\Exception\InvalidArgumentException;

class ClientFactory {

    const REDIS_VERSION_2x6 = '2.6';
    const REDIS_VERSION_2x8 = '2.8';
    const REDIS_VERSION_3x0 = '3.0';
    const REDIS_VERSION_3x2 = '3.2';
    const REDIS_VERSION_4x0 = '4.0';
    const REDIS_VERSION_5x0 = '5.0';
    const REDIS_VERSION_6x0 = '6.0';
    const REDIS_VERSION_DEFAULT = self::REDIS_VERSION_6x0;

    /**
     * @var string|null
     */
    protected static $defaultRedisVersion;

    /**
     * @var array
     */
    protected static $versions = [
        self::REDIS_VERSION_2x6 => RedisClient2x6::class,
        self::REDIS_VERSION_2x8 => RedisClient2x8::class,
        self::REDIS_VERSION_3x0 => RedisClient3x0::class,
        self::REDIS_VERSION_3x2 => RedisClient3x2::class,
        self::REDIS_VERSION_4x0 => RedisClient4x0::class,
        self::REDIS_VERSION_5x0 => RedisClient5x0::class,
        self::REDIS_VERSION_6x0 => RedisClient6x0::class,
    ];

    /**
     * @return string
     */
    public static function getDefaultRedisVersion() {
        return self::$defaultRedisVersion ?: self::REDIS_VERSION_DEFAULT;
    }

    /**
     * @param string $version
     * @throws InvalidArgumentException
     * @throws ErrorException
     * @return bool
     */
    public static function setDefaultRedisVersion($version) {
        if (self::$defaultRedisVersion == $version) {
            return true;
        }
        if (class_exists('\RedisClient\RedisClient', false)) {
            throw new ErrorException('You can setup default version only if class "\RedisClient\RedisClient" is not loaded.');
        }
        if (self::$defaultRedisVersion) {
            throw new ErrorException('Default Version is defined already.');
        }
        if (!array_key_exists($version, self::$versions)) {
            throw new InvalidArgumentException(
                'Invalid version. Supported versions are '. implode(',', array_keys(self::$versions))
            );
        }
        self::$defaultRedisVersion = $version;
        return true;
    }

    /**
     * @param null|array $config
     * @return RedisClient2x6|RedisClient2x8|RedisClient3x0|RedisClient3x2|RedisClient4x0|RedisClient5x0|RedisClient
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
     * @return RedisClient2x6|RedisClient2x8|RedisClient3x0|RedisClient3x2|RedisClient4x0|RedisClient5x0
     * @throws InvalidArgumentException
     */
    public static function createClientByVersion($version, $config = null) {
        list($major, $minor, $patch) = explode('.', $version .'.0.0');
        $ver = (int)$major .'.'. (int)$minor;

        $versions = array_keys(self::$versions);
        foreach ($versions as $v) {
            if ($v >= $ver) {
                $class = self::$versions[$v];
                if (!self::$defaultRedisVersion) {
                    if (self::setDefaultRedisVersion($v)) {
                        return new RedisClient($config);
                    }
                } elseif (self::$defaultRedisVersion == $v) {
                    return new RedisClient($config);
                }
                break;
            }
        }
        if (empty($class)) {
            throw new InvalidArgumentException(
                'RedisClient does not support Redis version '. $version .'. Please, use version '. end($versions)
            );
        }
        return new $class($config);
    }

}
