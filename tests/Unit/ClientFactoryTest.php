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
namespace Test\Unit;

use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Client\Version\RedisClient3x0;
use RedisClient\Client\Version\RedisClient3x2;
use RedisClient\Client\Version\RedisClient4x0;
use RedisClient\Client\Version\RedisClient5x0;
use RedisClient\Client\Version\RedisClient6x0;

use RedisClient\ClientFactory;

/**
 * @see ClientFactory
 */
class ClientFactoryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @see RedisProtocol::createClientByVersion
     */
    public function test_createClientByVersion() {
        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::create(['version' => '2.6']));
        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::create());

        $this->assertInstanceOf(RedisClient3x2::class, ClientFactory::createClientByVersion(3.2));
        $this->assertInstanceOf(RedisClient3x2::class, ClientFactory::createClientByVersion('3.2.2'));
        $this->assertInstanceOf(RedisClient3x2::class, ClientFactory::createClientByVersion('3.2.0'));
        $this->assertInstanceOf(RedisClient3x2::class, ClientFactory::createClientByVersion('3.2.x'));
        $this->assertInstanceOf(RedisClient3x2::class, ClientFactory::createClientByVersion('3.1'));
        $this->assertInstanceOf(RedisClient3x2::class, ClientFactory::createClientByVersion('3.1.5'));

        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::create());

        $this->assertInstanceOf(RedisClient3x0::class, ClientFactory::createClientByVersion('3.0'));
        $this->assertInstanceOf(RedisClient3x0::class, ClientFactory::createClientByVersion(3.0));
        $this->assertInstanceOf(RedisClient3x0::class, ClientFactory::createClientByVersion('3'));
        $this->assertInstanceOf(RedisClient3x0::class, ClientFactory::createClientByVersion(3));
        $this->assertInstanceOf(RedisClient3x0::class, ClientFactory::createClientByVersion('3.0.9'));
        $this->assertInstanceOf(RedisClient3x0::class, ClientFactory::createClientByVersion('3.0.6'));
        $this->assertInstanceOf(RedisClient3x0::class, ClientFactory::createClientByVersion('3.0.x'));
        $this->assertInstanceOf(RedisClient3x0::class, ClientFactory::createClientByVersion('2.9'));
        $this->assertInstanceOf(RedisClient3x0::class, ClientFactory::createClientByVersion(2.9));
        $this->assertInstanceOf(RedisClient3x0::class, ClientFactory::createClientByVersion('2.9.1'));

        $this->assertInstanceOf(RedisClient2x8::class, ClientFactory::createClientByVersion('2.8'));
        $this->assertInstanceOf(RedisClient2x8::class, ClientFactory::createClientByVersion(2.8));
        $this->assertInstanceOf(RedisClient2x8::class, ClientFactory::createClientByVersion('2.8.24'));
        $this->assertInstanceOf(RedisClient2x8::class, ClientFactory::createClientByVersion('2.7'));
        $this->assertInstanceOf(RedisClient2x8::class, ClientFactory::createClientByVersion(2.7));
        $this->assertInstanceOf(RedisClient2x8::class, ClientFactory::createClientByVersion('2.7.4'));

        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::createClientByVersion('2.6'));
        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::createClientByVersion(2.6));
        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::createClientByVersion('2.6.17'));
        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::createClientByVersion('2.6.17'));
        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::createClientByVersion('2.5'));
        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::createClientByVersion(2.5));
        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::createClientByVersion('2'));
        $this->assertInstanceOf(RedisClient2x6::class, ClientFactory::createClientByVersion(2));

        $this->assertInstanceOf(RedisClient6x0::class, ClientFactory::createClientByVersion('6.0.5'));
        $this->assertInstanceOf(RedisClient5x0::class, ClientFactory::createClientByVersion('5.0.5'));
    }
}
