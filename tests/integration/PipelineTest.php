<?php

namespace Test\Integration;

include_once(__DIR__. '/AbstractCommandsTest.php');

use RedisClient\Exception\ErrorResponseException;
use RedisClient\Pipeline\Pipeline;

class PipelineTest extends AbstractCommandsTest {

    public function test_separate() {
        $Redis = static::$Redis;

        /** @var Pipeline $Pipeline */
        $Pipeline = $Redis->pipeline();
        $this->assertInstanceOf(Pipeline::class, $Pipeline);

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
        $this->assertInstanceOf(Pipeline::class, $Pipeline);

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
        $this->assertInstanceOf(Pipeline::class, $Pipeline);

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
        $this->assertInstanceOf(Pipeline::class, $Pipeline);

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
        $this->assertInstanceOf(Pipeline::class, $Pipeline);

        $this->assertSame([true, 5, '5', true, 3, '3', ['5', '3']],
            $Redis->pipeline(function(Pipeline $Pipeline) {
                $Pipeline->set('foo', '4')->incr('foo')->get('foo');
                $Pipeline->set('bar', '2')->incr('bar')->get('bar');
                $Pipeline->mget(['foo', 'bar']);
        }));

        $result = $Redis->pipeline(function(Pipeline $Pipeline) {
            $Pipeline->incr('foo');
            $Pipeline->hincrby('foo', 'bar', '1');
            $Pipeline->incr('foo');
        });

        $this->assertSame(3, count($result));
        $this->assertSame(6, $result[0]);
        $this->assertInstanceOf(ErrorResponseException::class, $result[1]);
        $this->assertSame(7, $result[2]);
    }

}
