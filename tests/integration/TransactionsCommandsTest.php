<?php

namespace Test\Integration;

include_once(__DIR__. '/AbstractCommandsTest.php');

use RedisClient\Exception\ErrorResponseException;

class TransactionsCommandsTest extends AbstractCommandsTest {

    public function test_discard() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->multi());
        $this->assertSame('QUEUED', $Redis->set('foo', 'bar'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'foo'));
        $this->assertSame(true, $Redis->discard());

        $this->assertSame(null, $Redis->get('foo'));
        $this->assertSame(null, $Redis->get('bar'));

        $this->assertSame(true, $Redis->multi());
        $this->assertSame('QUEUED', $Redis->set('foo', 'bar'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'foo'));
        $this->assertSame('QUEUED', $Redis->hincrby('bar', 'foo', 1));
        $this->assertSame('QUEUED', $Redis->lpush('bar', 'foo'));
        $this->assertSame(true, $Redis->discard());

        $this->assertSame(null, $Redis->get('foo'));
        $this->assertSame(null, $Redis->get('bar'));

        try {
            $Redis->discard();
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('ERR DISCARD without MULTI', $Ex->getMessage());
        }
    }

    public function test_exec() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->multi());
        $this->assertSame('QUEUED', $Redis->set('foo', 'bar'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'foo'));
        $this->assertSame(true, $Redis->discard());

        $this->assertSame(null, $Redis->get('foo'));
        $this->assertSame(null, $Redis->get('bar'));

        $this->assertSame(true, $Redis->multi());
        $this->assertSame('QUEUED', $Redis->set('foo', 'bar'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'foo'));
        $this->assertSame([true, true], $Redis->exec());

        $this->assertSame('bar', $Redis->get('foo'));
        $this->assertSame('foo', $Redis->get('bar'));

        $this->assertSame(true, $Redis->multi());
        $this->assertSame('QUEUED', $Redis->set('foo', 'foo'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'bar'));
        $this->assertSame('QUEUED', $Redis->get('bar'));
        $this->assertSame('QUEUED', $Redis->hincrby('foo', 'foo', 1));
        $this->assertSame('QUEUED', $Redis->lpush('bar', 'bar'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'new'));
        $this->assertSame('QUEUED', $Redis->get('bar'));
        $result = $Redis->exec();

        $this->assertSame(7, count($result));
        $this->assertSame(true, $result[0]);
        $this->assertSame(true, $result[1]);
        $this->assertSame('bar', $result[2]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[3]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[4]);
        $this->assertSame(true, $result[5]);
        $this->assertSame('new', $result[6]);

        try {
            $Redis->exec();
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('ERR EXEC without MULTI', $Ex->getMessage());
        }
    }

    public function test_multi() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->multi());
        $this->assertSame('QUEUED', $Redis->set('foo', 'bar'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'foo'));
        $this->assertSame(true, $Redis->discard());

        $this->assertSame(null, $Redis->get('foo'));
        $this->assertSame(null, $Redis->get('bar'));

        $this->assertSame(true, $Redis->multi());
        $this->assertSame('QUEUED', $Redis->set('foo', 'bar'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'foo'));
        $this->assertSame([true, true], $Redis->exec());

        $this->assertSame(true, $Redis->multi());
        $this->assertSame(true, $Redis->discard());
        $this->assertSame(true, $Redis->multi());
        $this->assertSame([], $Redis->exec());

        $this->assertSame(true, $Redis->multi());
        try {
            $Redis->multi();
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('ERR MULTI calls can not be nested', $Ex->getMessage());
        }
        $this->assertSame(true, $Redis->discard());
    }

    public function test_unwatch() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->unwatch());

        $this->assertSame(true, $Redis->watch(['foo', 'bar']));
        $this->assertSame(true, $Redis->unwatch());

        $this->assertSame(true, $Redis->watch(['foo', 'bar']));
        $this->assertSame(true, $Redis->multi());
        $this->assertSame('QUEUED', $Redis->set('foo', 'bar'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'foo'));
        $this->assertSame(true, $Redis->discard());
        $this->assertSame(true, $Redis->unwatch());

        $this->assertSame(true, $Redis->watch(['foo', 'bar']));
        $this->assertSame(true, $Redis->multi());
        $Redis->unwatch();
        $this->assertSame(true, $Redis->discard());
    }

    public function test_watch() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->watch(['foo', 'bar']));
        $this->assertSame(true, $Redis->unwatch());

        $this->assertSame(true, $Redis->watch(['foo', 'bar']));
        $this->assertSame(true, $Redis->multi());
        $this->assertSame('QUEUED', $Redis->set('foo', 'bar'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'foo'));
        $this->assertSame(true, $Redis->discard());

        $this->assertSame(true, $Redis->watch(['foo', 'bar']));
        $this->assertSame(true, $Redis->multi());
        $Redis->unwatch();
        try {
            $Redis->watch(['foo', 'bar']);
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('ERR WATCH inside MULTI is not allowed', $Ex->getMessage());
        }
        $this->assertSame(true, $Redis->discard());
    }

}
