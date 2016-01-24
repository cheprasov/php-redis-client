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
namespace Test\Unit;

use RedisClient\Client\AbstractRedisClient;

/**
 * @see AbstractRedisClient
 */
class AbstractRedisClientTest extends \PHPUnit_Framework_TestCase {

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractRedisClient
     */
    protected function getAbstractRedisClientMock() {
        $Mock = $this->getMockForAbstractClass(AbstractRedisClient::class);
        return $Mock;
    }

    /**
     * @see AbstractRedisClient::parseRawString
     */
    public function test_parseRawString() {
        $Method = new \ReflectionMethod(AbstractRedisClient::class, 'parseRawString');
        $Method->setAccessible(true);

        $Client = $this->getAbstractRedisClientMock();

        $this->assertSame(['SET', 'foo', 'bar'], $Method->invoke($Client, 'SET foo bar'));
        $this->assertSame(['SET', 'foo', 'bar'], $Method->invoke($Client, '  SET    foo    bar   '));
        $this->assertSame(['SET', 'foo', 'hello world'], $Method->invoke($Client, 'SET foo "hello world"'));
        $this->assertSame(['SET', '', 'hello world'], $Method->invoke($Client, 'SET "" "hello world"'));
        $this->assertSame(['SET', 'some key', 'hello world'], $Method->invoke($Client, 'SET "some key" "hello world"'));
        $this->assertSame(
            ['SET', 'some "key"', 'hello my "little" world'],
            $Method->invoke($Client, 'SET "some \"key\"" "hello my \"little\" world"')
        );
        $this->assertSame(['SET', "\x0\xFF", ''], $Method->invoke($Client, "SET \"\x0\xFF\" \"\""));
        $this->assertSame(['SET', "\x0", "\x0"], $Method->invoke($Client, "SET \"\x0\" \"\x0\""));

        $this->assertSame(['PING'], $Method->invoke($Client, 'PING'));
        $this->assertSame(['PING'], $Method->invoke($Client, ' PING '));

        $this->assertSame(['DEL', 'foo', 'bar'], $Method->invoke($Client, 'DEL foo bar'));
        $this->assertSame(['DEL', 'foo bar', 'some key'], $Method->invoke($Client, 'DEL "foo bar" "some key"'));
        $this->assertSame(['SET', 'foo bar', "some\r\nkey"], $Method->invoke($Client, "SET \"foo bar\" \"some\r\nkey\""));
        $this->assertSame(['DEL', 'a', 'b', 'c', 'd', 'e', 'f'], $Method->invoke($Client, 'DEL a  b   c    d e  f  '));
        $this->assertSame(['DEL', '""', '"'], $Method->invoke($Client, 'DEL "\"\"" "\""'));
        $this->assertSame(['DEL', '\"\"', '\"'], $Method->invoke($Client, 'DEL "\\\"\\\"" "\\\""'));

        $this->assertSame(['SET', 'foo-bar', 'some-value'], $Method->invoke($Client, 'SET foo-bar some-value'));
        $this->assertSame(['SET', "foo\x0", 'some-value'], $Method->invoke($Client, "SET foo\x0 some-value"));
        $this->assertSame(['SET', "\x0", 'some-value'], $Method->invoke($Client, "SET \"\x0\" some-value"));
        $this->assertSame(['SET', "\x0\x1\x2", "\x0\x1\x2"], $Method->invoke($Client, "SET \"\x0\x1\x2\" \"\x0\x1\x2\""));
        $this->assertSame(['SET', "\x0 \x1 \x2", "\x0 \x1 \x2"], $Method->invoke($Client, "SET \"\x0 \x1 \x2\" \"\x0 \x1 \x2\""));
    }

    /**
     * @see AbstractRedisClient::getStructure
     */
    public function test_getStructure() {
        $Method = new \ReflectionMethod(AbstractRedisClient::class, 'getStructure');
        $Method->setAccessible(true);

        $Client = $this->getAbstractRedisClientMock();

        $this->assertSame(['PING'], $Method->invoke($Client, ['PING']));
        $this->assertSame(['PING'], $Method->invoke($Client, ['PING'], []));
        $this->assertSame(['SET', 'foo', 'bar'], $Method->invoke($Client, ['SET'], ['foo', 'bar']));
        $this->assertSame(['SET', 'foo', 'bar'], $Method->invoke($Client, ['SET'], [['foo', 'bar']]));
        $this->assertSame(['SET', 'a', 'b', 'c', 'd', 'e'], $Method->invoke($Client, ['SET'], ['a', ['b', 'c'], 'd', ['e']]));
    }

}
