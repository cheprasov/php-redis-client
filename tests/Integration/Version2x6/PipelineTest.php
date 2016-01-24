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
namespace Test\Integration\Version2x6;

use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\Exception\ErrorResponseException;
use RedisClient\Pipeline\Pipeline;
use RedisClient\Pipeline\PipelineInterface;

class PipelineTest extends \PHPUnit_Framework_TestCase {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_2x6_1;

    /**
     * @var RedisClient2x6
     */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient2x6([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass() {
        static::$Redis->flushall();
    }

    /**
     * @inheritdoc
     */
    protected function setUp() {
        static::$Redis->flushall();
    }

    public function test_separate() {
        $Redis = static::$Redis;

        /** @var Pipeline $Pipeline */
        $Pipeline = $Redis->pipeline();
        $this->assertInstanceOf(PipelineInterface::class, $Pipeline);

        $this->assertSame($Pipeline, $Pipeline->set('foo', '4'));
        $this->assertSame($Pipeline, $Pipeline->incr('foo'));
        $this->assertSame($Pipeline, $Pipeline->get('foo'));
        $this->assertSame($Pipeline, $Pipeline->set('bar', '2'));
        $this->assertSame($Pipeline, $Pipeline->incr('bar'));
        $this->assertSame($Pipeline, $Pipeline->get('bar'));
        $this->assertSame($Pipeline, $Pipeline->mget(['foo', 'bar']));
        $this->assertSame([true, 5, '5', true, 3, '3', ['5', '3']], $Redis->pipeline($Pipeline));

        /** @var Pipeline $Pipeline */
        $Pipeline = $Redis->pipeline();
        $this->assertInstanceOf(PipelineInterface::class, $Pipeline);

        $this->assertSame($Pipeline, $Pipeline->incr('foo'));
        $this->assertSame($Pipeline, $Pipeline->hincrby('foo', 'bar', '1'));
        $this->assertSame($Pipeline, $Pipeline->incr('foo'));

        $result = $Redis->pipeline($Pipeline);

        $this->assertSame(3, count($result));
        $this->assertSame(6, $result[0]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[1]);
        $this->assertSame(7, $result[2]);
    }

    public function test_pipeline() {
        $Redis = static::$Redis;

        /** @var Pipeline $Pipeline */
        $Pipeline = $Redis->pipeline();
        $this->assertInstanceOf(PipelineInterface::class, $Pipeline);

        $this->assertSame(
            [true, 5, '5', true, 3, '3', ['5', '3']],
            $Redis->pipeline(
                $Pipeline->set('foo', '4')->incr('foo')->get('foo')
                    ->set('bar', '2')->incr('bar')->get('bar')
                    ->mget(['foo', 'bar'])
            )
        );

        /** @var Pipeline $Pipeline */
        $Pipeline = $Redis->pipeline();
        $this->assertInstanceOf(PipelineInterface::class, $Pipeline);

        $result = $Redis->pipeline($Pipeline->incr('foo')->hincrby('foo', 'bar', '1')->incr('foo'));
        $this->assertSame(3, count($result));
        $this->assertSame(6, $result[0]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[1]);
        $this->assertSame(7, $result[2]);
    }

    public function test_closure() {
        $Redis = static::$Redis;

        /** @var Pipeline $Pipeline */
        $Pipeline = $Redis->pipeline();
        $this->assertInstanceOf(PipelineInterface::class, $Pipeline);

        $this->assertSame([true, 5, '5', true, 3, '3', ['5', '3']],
            $Redis->pipeline(function(PipelineInterface $Pipeline) {
                /** @var Pipeline $Pipeline */
                $Pipeline->set('foo', '4')->incr('foo')->get('foo');
                $Pipeline->set('bar', '2')->incr('bar')->get('bar');
                $Pipeline->mget(['foo', 'bar']);
        }));

        $result = $Redis->pipeline(function(PipelineInterface $Pipeline) {
            /** @var Pipeline $Pipeline */
            $Pipeline->incr('foo');
            $Pipeline->hincrby('foo', 'bar', '1');
            $Pipeline->incr('foo');
        });

        $this->assertSame(3, count($result));
        $this->assertSame(6, $result[0]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[1]);
        $this->assertSame(7, $result[2]);
    }

    public function test_transaction() {
        $Redis = static::$Redis;

        /** @var Pipeline $Pipeline */
        $Pipeline = $Redis->pipeline();
        $this->assertInstanceOf(PipelineInterface::class, $Pipeline);
        $result = $Redis->pipeline(function(PipelineInterface $Pipeline) {
            /** @var Pipeline $Pipeline */
            $Pipeline
                ->multi()
                ->set('foo', 'foo')
                ->set('bar', 'bar')
                ->get('bar')
                ->hincrby('foo', 'foo', 1)
                ->lpush('bar', 'bar')
                ->set('bar', 'new')
                ->get('bar')
                ->echo('hello word')
                ->exec();
        });

        $this->assertSame(10, count($result));
        $this->assertSame(true, $result[0]);
        $this->assertSame('QUEUED', $result[1]);
        $this->assertSame('QUEUED', $result[2]);
        $this->assertSame('QUEUED', $result[3]);
        $this->assertSame('QUEUED', $result[4]);
        $this->assertSame('QUEUED', $result[5]);
        $this->assertSame('QUEUED', $result[6]);
        $this->assertSame('QUEUED', $result[7]);
        $this->assertSame('QUEUED', $result[8]);

        $result = $result[9];

        $this->assertSame(8, count($result));
        $this->assertSame(true, $result[0]);
        $this->assertSame(true, $result[1]);
        $this->assertSame('bar', $result[2]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[3]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[4]);
        $this->assertSame(true, $result[5]);
        $this->assertSame('new', $result[6]);
        $this->assertSame('hello word', $result[7]);
    }

}
