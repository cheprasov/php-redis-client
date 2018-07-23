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
namespace Test\Integration\Version2x6;

include_once(__DIR__ . '/../BaseVersionTest.php');

/**
 * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait
 */
class CommonTest extends \Test\Integration\BaseVersionTest {

    /**
     * @see \RedisClient\Client\AbstractRedisClient::executeRawString
     */
    public function test_executeRawString() {
        $Redis = static::$Redis;

        $this->assertSame('PONG', $Redis->executeRawString('PING'));

        $this->assertSame(true, $Redis->executeRawString('SET foo bar'));
        $this->assertSame('bar', $Redis->executeRawString('GET foo'));

        $this->assertSame(true, $Redis->executeRawString('SET foo "hello world"'));
        $this->assertSame('hello world', $Redis->executeRawString('GET foo'));

        $this->assertSame(true, $Redis->executeRawString("SET \"\" \"String\r\nwith\r\nnewlines\""));
        $this->assertSame("String\r\nwith\r\nnewlines", $Redis->executeRawString('GET ""'));
    }

    /**
     * @see \RedisClient\Client\AbstractRedisClient::executeRaw
     */
    public function test_executeRaw() {
        $Redis = static::$Redis;

        $this->assertSame('PONG', $Redis->executeRaw(['PING']));

        $this->assertSame(true, $Redis->executeRaw(['SET', 'foo', 'bar']));
        $this->assertSame('bar', $Redis->executeRaw(['GET', 'foo']));

        $this->assertSame(true, $Redis->executeRaw(['SET', 'foo', 'hello world']));
        $this->assertSame('hello world', $Redis->executeRaw(['GET', 'foo']));

        $this->assertSame(true, $Redis->executeRaw(['SET', '', "String\r\nwith\r\nnewlines"]));
        $this->assertSame("String\r\nwith\r\nnewlines", $Redis->executeRaw(['GET', '']));
    }

    public function test_bigData() {
        $Redis = static::$Redis;

        $string = str_repeat(sha1(microtime(true)), 1024 * 1024);
        $md5 = md5($string);
        $this->assertSame(1024 * 1024 * 40, strlen($string));

        $this->assertSame(true, $Redis->set('foo', $string));
        $this->assertSame($string, $Redis->get('foo'));
        $this->assertSame($md5, md5($Redis->get('foo')));
    }

}
