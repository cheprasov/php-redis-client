<?php

namespace RedisClient\Pattern\Traits;

trait SingletonTrait {

    protected static $instance;

    protected function __construct() {
    }

    public static function getInstance() {
        if (!static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

}
