<?php

class TestSuite extends PHPUnit_Framework_TestCase {

    /**
     * @var \RedisClient\RedisClient
     */
    protected $Redis;

    public function doRun() {

    }

    public function setUp() {
        $this->Redis = new \RedisClient\RedisClient();
    }

    public function testRedis() {
        $this->Redis->del('test');
    }



}
