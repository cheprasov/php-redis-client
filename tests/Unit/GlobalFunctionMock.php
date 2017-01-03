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
namespace Test\Unit;

class GlobalFunctionMock {

    protected static $mockedFunctions = [];

    /**
     * @param string $namespace
     * @param string $name
     * @param callable $function
     */
    public static function mockFunction($namespace, $name, $function) {
        if (empty(static::$mockedFunctions[$name])) {
            $eval = [];
            if ($namespace) {
                $eval[] = "namespace {$namespace};";
            }
            $eval[] = "function {$name}(){
                return call_user_func_array('\\Test\\Unit\\GlobalFunctionMock::{$name}', func_get_args());
            };";
            eval(implode("\n", $eval));
        }
        static::$mockedFunctions[$name] = [
            'name' => $name,
            'called' => 0,
            'function' => $function,
        ];
    }

    /**
     * @param string $name
     * @return int
     */
    public static function getCountCalls($name) {
        if (empty(static::$mockedFunctions[$name]['called'])) {
            return 0;
        }
        return static::$mockedFunctions[$name]['called'];
    }

    /**
     * @param string $name
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($name, $args) {
        if (empty(static::$mockedFunctions[$name])) {
            if (is_callable($name)) {
                return call_user_func_array($name, $args);
            }
            throw new \Exception("Can not to call function '{$name}'");
        }
        $function = static::$mockedFunctions[$name]['function'];
        $result = call_user_func_array($function, $args);
        static::$mockedFunctions[$name]['called'] += 1;
        return $result;
    }

}
