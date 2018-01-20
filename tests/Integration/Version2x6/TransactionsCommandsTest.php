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

use RedisClient\Exception\ErrorResponseException;

/**
 * @see \RedisClient\Command\Traits\Version2x6\TransactionsCommandsTrait
 */
class TransactionsCommandsTest extends \Test\Integration\BaseVersionTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x6\TransactionsCommandsTrait::discard
     */
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
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\TransactionsCommandsTrait::exec
     */
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
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\TransactionsCommandsTrait::multi
     */
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
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
        $this->assertSame(true, $Redis->discard());
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\TransactionsCommandsTrait::unwatch
     */
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

    /**
     * @see \RedisClient\Command\Traits\Version2x6\TransactionsCommandsTrait::watch
     */
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
        $this->assertSame('QUEUED', $Redis->set('foo', 'bar'));
        $this->assertSame('QUEUED', $Redis->set('bar', 'foo'));
        $this->assertSame([true, true], $Redis->exec());

        $this->assertSame(true, $Redis->watch(['foo', 'bar']));
        $this->assertSame(true, $Redis->multi());
        $Redis->unwatch();
        try {
            $Redis->watch(['foo', 'bar']);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
        $this->assertSame(true, $Redis->discard());
    }

}
