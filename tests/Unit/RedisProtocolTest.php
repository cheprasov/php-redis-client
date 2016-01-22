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


use RedisClient\Connection\StreamConnection;
use RedisClient\Exception\ErrorResponseException;
use RedisClient\Protocol\RedisProtocol;

class RedisProtocolTest extends \PHPUnit_Framework_TestCase {

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|StreamConnection
     */
    protected function getConnectionMock() {
        $ConnectionMock = $this->getMockBuilder(StreamConnection::class)
            ->setMethods(['readLine', 'read', 'write'])
            ->setConstructorArgs([0, 0])
            ->getMock();

        return $ConnectionMock;
    }

    /**
     * @see RedisProtocol::packProtocolBulkString
     */
    public function test_packProtocolBulkString() {
        $Method = new \ReflectionMethod(RedisProtocol::class, 'packProtocolBulkString');
        $Method->setAccessible(true);

        $Protocol = new RedisProtocol($this->getConnectionMock());

        $this->assertSame("$3\r\nfoo\r\n", $Method->invoke($Protocol, 'foo'));
        $this->assertSame("$7\r\nfoo bar\r\n", $Method->invoke($Protocol, 'foo bar'));
        $this->assertSame("$8\r\nfoo\r\nbar\r\n", $Method->invoke($Protocol, "foo\r\nbar"));

        $this->assertSame("$1\r\n1\r\n", $Method->invoke($Protocol, 1));
        $this->assertSame("$3\r\n-10\r\n", $Method->invoke($Protocol, -10));
        $this->assertSame("$1\r\n\x0\r\n", $Method->invoke($Protocol, chr(0)));

        $this->assertSame("$3\r\n\x0\xff\x0\r\n", $Method->invoke($Protocol, chr(0).chr(255).chr(0)));
        $this->assertSame("$0\r\n\r\n", $Method->invoke($Protocol, null));

        $this->assertSame("$3\r\n2.5\r\n", $Method->invoke($Protocol, 2.5));
        $this->assertSame("$3\r\n0.5\r\n", $Method->invoke($Protocol, 0.5));
        $this->assertSame("$4\r\n-0.5\r\n", $Method->invoke($Protocol, -0.5));
        $this->assertSame("$4\r\n-0.5\r\n", $Method->invoke($Protocol, -0.5));
        $this->assertSame("$6\r\n-1.333\r\n", $Method->invoke($Protocol, -1.333));

        $this->assertSame("$1\r\n1\r\n", $Method->invoke($Protocol, true));
        $this->assertSame("$0\r\n\r\n", $Method->invoke($Protocol, false));
    }

    /**
     * @see RedisProtocol::packProtocolNull
     */
    public function test_packProtocolNull() {
        $Method = new \ReflectionMethod(RedisProtocol::class, 'packProtocolNull');
        $Method->setAccessible(true);

        $Protocol = new RedisProtocol($this->getConnectionMock());

        $this->assertSame("$-1\r\n", $Method->invoke($Protocol));
    }

    /**
     * @see RedisProtocol::packProtocolArray
     */
    public function test_packProtocolArray() {
        $Method = new \ReflectionMethod(RedisProtocol::class, 'packProtocolArray');
        $Method->setAccessible(true);

        $Protocol = new RedisProtocol($this->getConnectionMock());

        $this->assertSame("*3\r\n$1\r\n1\r\n$1\r\n2\r\n$1\r\n3\r\n", $Method->invoke($Protocol, [1, 2, 3]));
        $this->assertSame("*0\r\n", $Method->invoke($Protocol, []));
        $this->assertSame("*3\r\n$3\r\nSET\r\n$3\r\nfoo\r\n$3\r\nbar\r\n", $Method->invoke($Protocol, ['SET', 'foo', 'bar']));
        $this->assertSame("*3\r\n$3\r\nSET\r\n$0\r\n\r\n$3\r\nbar\r\n", $Method->invoke($Protocol, ['SET', '', 'bar']));
        $this->assertSame("*3\r\n$3\r\nSET\r\n$1\r\na\r\n$2\r\nbc\r\n", $Method->invoke($Protocol, ['SET', 'a', 'bc']));
    }

    /**
     * @see RedisProtocol::pack
     */
    public function test_pack() {
        $Method = new \ReflectionMethod(RedisProtocol::class, 'pack');
        $Method->setAccessible(true);

        $Protocol = new RedisProtocol($this->getConnectionMock());

        $this->assertSame("*3\r\n$1\r\n1\r\n$1\r\n2\r\n$1\r\n3\r\n", $Method->invoke($Protocol, [1, 2, 3]));
        $this->assertSame("*0\r\n", $Method->invoke($Protocol, []));
        $this->assertSame("*3\r\n$3\r\nSET\r\n$3\r\nfoo\r\n$3\r\nbar\r\n", $Method->invoke($Protocol, ['SET', 'foo', 'bar']));
        $this->assertSame("*3\r\n$3\r\nSET\r\n$0\r\n\r\n$3\r\nbar\r\n", $Method->invoke($Protocol, ['SET', '', 'bar']));
        $this->assertSame("*3\r\n$3\r\nSET\r\n$1\r\na\r\n$2\r\nbc\r\n", $Method->invoke($Protocol, ['SET', 'a', 'bc']));

        $this->assertSame("$3\r\nfoo\r\n", $Method->invoke($Protocol, 'foo'));
        $this->assertSame("$7\r\nfoo bar\r\n", $Method->invoke($Protocol, 'foo bar'));
        $this->assertSame("$8\r\nfoo\r\nbar\r\n", $Method->invoke($Protocol, "foo\r\nbar"));

        $this->assertSame("$1\r\n1\r\n", $Method->invoke($Protocol, 1));
        $this->assertSame("$3\r\n-10\r\n", $Method->invoke($Protocol, -10));
        $this->assertSame("$1\r\n\x0\r\n", $Method->invoke($Protocol, chr(0)));

        $this->assertSame("$3\r\n\x0\xff\x0\r\n", $Method->invoke($Protocol, chr(0).chr(255).chr(0)));
        $this->assertSame("$0\r\n\r\n", $Method->invoke($Protocol, null));

        $this->assertSame("$3\r\n2.5\r\n", $Method->invoke($Protocol, 2.5));
        $this->assertSame("$3\r\n0.5\r\n", $Method->invoke($Protocol, 0.5));
        $this->assertSame("$4\r\n-0.5\r\n", $Method->invoke($Protocol, -0.5));
        $this->assertSame("$4\r\n-0.5\r\n", $Method->invoke($Protocol, -0.5));
        $this->assertSame("$6\r\n-1.333\r\n", $Method->invoke($Protocol, -1.333));

        $this->assertSame("$1\r\n1\r\n", $Method->invoke($Protocol, true));
        $this->assertSame("$0\r\n\r\n", $Method->invoke($Protocol, false));
    }

    /**
     * @see RedisProtocol::read
     */
    public function test_read() {
        $Method = new \ReflectionMethod(RedisProtocol::class, 'read');
        $Method->setAccessible(true);

        $Protocol = new RedisProtocol($ConnectionMock = $this->getConnectionMock());

        $ConnectionMock->method('readLine')->willReturn(
            "*3\r\n", "$1\r\n", ":2\r\n", "$1\r\n",
            "+OK\r\n",
            "+PING-PONG\r\n",
            ":100\r\n",
            ":-42\r\n",
            ":-1\r\n",
            "*3\r\n", "$3\r\n", "$3\r\n", "$3\r\n",
            "*3\r\n", "$3\r\n", "$0\r\n", "$-1\r\n",
            "$6\r\n",
            '-ERR SomeError'
        );

        $ConnectionMock->method('read')->willReturn(
            "1\r\n", "3\r\n",
            "SET\r\n", "foo\r\n", "bar\r\n",
            "SET\r\n", "\r\n",
            "\r\n\xff\x0\r\n\r\n"
        );

        $this->assertSame(['1', 2, '3'], $Method->invoke($Protocol));
        $this->assertSame(true, $Method->invoke($Protocol));
        $this->assertSame('PING-PONG', $Method->invoke($Protocol));
        $this->assertSame(100, $Method->invoke($Protocol));
        $this->assertSame(-42, $Method->invoke($Protocol));
        $this->assertSame(-1, $Method->invoke($Protocol));
        $this->assertSame(['SET', 'foo', 'bar'], $Method->invoke($Protocol));
        $this->assertSame(['SET', '', null], $Method->invoke($Protocol));
        $this->assertSame("\r\n\xff\x0\r\n", $Method->invoke($Protocol));
        $this->assertInstanceOf(ErrorResponseException::class, $Method->invoke($Protocol));
    }
}
