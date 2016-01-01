<?php

namespace Test\Integration;

include_once(__DIR__. '/AbstractCommandsTest.php');

use RedisClient\Exception\ErrorResponseException;
use RedisClient\RedisClient;

class PipelineTest extends AbstractCommandsTest {

    public function test_separate() {
        $Redis = static::$Redis;

        $this->assertSame($Redis, $Redis->pipeline());
        $this->assertSame($Redis, $Redis->set('foo', '4'));
        $this->assertSame($Redis, $Redis->incr('foo'));
        $this->assertSame($Redis, $Redis->get('foo'));
        $this->assertSame($Redis, $Redis->set('bar', '2'));
        $this->assertSame($Redis, $Redis->incr('bar'));
        $this->assertSame($Redis, $Redis->get('bar'));
        $this->assertSame($Redis, $Redis->mget(['foo', 'bar']));
        $this->assertSame([
            true, 5, '5',
            true, 3, '3',
            ['foo' => '5', 'bar' => '3']
        ], $Redis->executePipeline());

        $this->assertSame($Redis, $Redis->pipeline());
        $this->assertSame($Redis, $Redis->incr('foo'));
        $this->assertSame($Redis, $Redis->hincrby('foo', 'bar', '1'));
        $this->assertSame($Redis, $Redis->incr('foo'));
        $result = $Redis->executePipeline();

        $this->assertSame(3, count($result));
        $this->assertSame(6, $result[0]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[1]);
        $this->assertSame(7, $result[2]);
    }

    public function test_pipeline() {
        $Redis = static::$Redis;
        $this->assertSame([
                true, 5, '5',
                true, 3, '3',
                ['foo' => '5', 'bar' => '3']
            ],
            $Redis->pipeline()
                ->set('foo', '4')->incr('foo')->get('foo')
                ->set('bar', '2')->incr('bar')->get('bar')
                ->mget(['foo', 'bar'])
                ->executePipeline()
        );

        $result = $Redis->pipeline()
            ->incr('foo')
            ->hincrby('foo', 'bar', '1')
            ->incr('foo')
            ->executePipeline();

        $this->assertSame(3, count($result));
        $this->assertSame(6, $result[0]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[1]);
        $this->assertSame(7, $result[2]);
    }

    public function test_closure() {
        $Redis = static::$Redis;

        $this->assertSame([
            true, 5, '5',
            true, 3, '3',
            ['foo' => '5', 'bar' => '3']
        ], $Redis->pipeline(function(RedisClient $Redis) {
            $Redis->set('foo', '4');
            $Redis->incr('foo');
            $Redis->get('foo');
            $Redis->set('bar', '2');
            $Redis->incr('bar');
            $Redis->get('bar');
            $Redis->mget(['foo', 'bar']);
        }));

        $result = $Redis->pipeline(function(RedisClient $Redis) {
            $Redis->pipeline();
            $Redis->incr('foo');
            $Redis->hincrby('foo', 'bar', '1');
            $Redis->incr('foo');
        });

        $this->assertSame(3, count($result));
        $this->assertSame(6, $result[0]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[1]);
        $this->assertSame(7, $result[2]);
    }

}
