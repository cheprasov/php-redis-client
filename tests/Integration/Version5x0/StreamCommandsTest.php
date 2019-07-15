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
namespace Test\Integration\Version5x0;

include_once(__DIR__ . '/../BaseVersionTest.php');

/**
 * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait
 */
class StreamCommandsTest extends \Test\Integration\BaseVersionTest {

    /**
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xack
     */
    public function test_xack() {
        $result = static::$Redis->xack('mystream','mygroup', '1526569495631-0');
        $this->assertEquals(0, $result);
    }

    /**
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xadd
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xlen
     */
    public function test_xadd() {
        $this->assertEquals(0, static::$Redis->xlen('mystream'));

        $result = static::$Redis->xadd('mystream', '*', ['name' => 'Sara', 'surname' => 'OConnor']);
        $this->assertEquals(1, preg_match('/^\d+-\d$/', $result));
        $this->assertEquals(1, static::$Redis->xlen('mystream'));

        $result = static::$Redis->xadd('mystream', '*', ['field1' => 'value1', 'field2' => 'value2', 'field3' => 'value3']);
        $this->assertEquals(1, preg_match('/^\d+-\d$/', $result));
        $this->assertEquals(2, static::$Redis->xlen('mystream'));
    }

}
