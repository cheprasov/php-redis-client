<?php

namespace Test\Integration;

include_once(__DIR__. '/AbstractCommandsTest.php');

use RedisClient\Exception\ErrorResponseException;

class ConnectionCommandsTest extends AbstractCommandsTest {

    public function test_auth() {
        $Redis = static::$Redis;

        try {
            $this->assertSame(true, $Redis->auth('password'));
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('ERR Client sent AUTH, but no password is set', $Ex->getMessage());
        }
    }

    public function test_echo() {
        $Redis = static::$Redis;

        $this->assertSame('message', $Redis->echoMessage('message'));
        $this->assertSame('', $Redis->echoMessage(''));
        $this->assertSame('foo bar', $Redis->echoMessage('foo bar'));
    }

    public function test_ping() {
        $Redis = static::$Redis;

        $this->assertSame('PONG', $Redis->ping());
        $this->assertSame('message', $Redis->ping('message'));
        $this->assertSame('', $Redis->ping(''));
        $this->assertSame('foo bar', $Redis->ping('foo bar'));
    }

    /*
    public function test_quit() {
        $Redis = static::$Redis;
        $this->assertSame(true, $Redis->quit());
    }
    */

    public function test_select() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->set('foo', 'db 0'));
        $this->assertSame('db 0', $Redis->get('foo'));

        $this->assertSame(true, $Redis->select(1));
        $this->assertSame(true, $Redis->set('foo', 'db 1'));
        $this->assertSame('db 1', $Redis->get('foo'));

        $this->assertSame(true, $Redis->select(0));
        $this->assertSame('db 0', $Redis->get('foo'));

        $this->assertSame(true, $Redis->select(2));
        $this->assertSame(null, $Redis->get('foo'));

        $this->assertSame(true, $Redis->select(0));
        $this->assertSame('db 0', $Redis->get('foo'));

        $this->assertSame(true, $Redis->select(1));
        $this->assertSame('db 1', $Redis->get('foo'));
    }

}
