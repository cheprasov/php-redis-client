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
namespace Test\Integration\Version4x0;

include_once(__DIR__ . '/../BaseVersionTest.php');

/**
 * @see \RedisClient\Command\Traits\Version4x0\MemoryCommandsTrait
 */
class MemoryCommandsTest extends \Test\Integration\BaseVersionTest {

    /**
     * @see \RedisClient\Command\Traits\Version4x0\MemoryCommandsTrait::memoryDoctor
     */
    public function test_memoryDoctor() {
        $Redis = static::$Redis;
        $res = $Redis->memoryDoctor();
        $this->assertSame(true, is_string($res));
        $this->assertSame(true, strlen($res) > 0);
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\MemoryCommandsTrait::memoryHelp
     */
    public function test_memoryHelp() {
        $Redis = static::$Redis;
        $res = $Redis->memoryHelp();
        $this->assertSame(true, is_array($res));
        $this->assertSame(true, count($res) > 0);
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\MemoryCommandsTrait::memoryUsage
     */
    public function test_memoryUsage() {
        $Redis = static::$Redis;
        $this->assertSame(null, $Redis->memoryUsage('foo'));
        $this->assertSame(true, $Redis->set('foo', 'bar'));
        $this->assertSame(true, $Redis->memoryUsage('foo') > 0);
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\MemoryCommandsTrait::memoryStats
     */
    public function test_memoryStats() {
        $Redis = static::$Redis;
        $res = $Redis->memoryStats();
        $this->assertSame(true, is_array($res));
        $this->assertSame(true, count($res) > 0);
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\MemoryCommandsTrait::memoryPurge
     */
    public function test_memoryPurge() {
        $Redis = static::$Redis;
        $this->assertSame(true, $Redis->memoryPurge());
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\MemoryCommandsTrait::memoryMallocStats
     */
    public function test_memoryMallocStats() {
        $Redis = static::$Redis;
        $res = $Redis->memoryMallocStats();
        $this->assertSame(true, is_string($res));
        $this->assertSame(true, strlen($res) > 0);
    }
}
