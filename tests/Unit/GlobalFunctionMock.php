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
     * @param string $fullname
     * @param callable $function
     */
    public static function mockFunction($fullname, $function) {
        if (false !== strpos($fullname, '::')) {
            list($namespace, $name) = explode('::', $fullname, 2);
        } else {
            $namespace = '';
            $name = $fullname;
        }
        if (empty(static::$mockedFunctions[$fullname])) {
            $eval = [];
            if ($namespace) {
                $eval[] = "namespace {$namespace};";
            }
            $eval[] = "function {$name}(){
                return \\Test\\Unit\\GlobalFunctionMock::invokeMockedFunction(
                    '{$fullname}',
                    '{$name}',
                    '{$namespace}',
                    func_get_args()
                );
            };";
            eval(implode("\n", $eval));
        }
        static::$mockedFunctions[$fullname] = [
            'fullname' => $fullname,
            'name' => $name,
            'namespace' => $namespace,
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
     * @param $fullname
     * @param $name
     * @param $namespace
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public static function invokeMockedFunction($fullname, $name, $namespace, $args) {
        if (empty(static::$mockedFunctions[$fullname])) {
            if (is_callable('\\' . $name)) {
                $e = call_user_func_array('\\' . $name, $args);
                var_dump($e);exit;
                return $e;
            }
            throw new \Exception("Can not to call function '{$name}'");
        }
        $function = static::$mockedFunctions[$fullname]['function'];
        $result = call_user_func_array($function, $args);
        static::$mockedFunctions[$fullname]['called'] += 1;
        return $result;
    }

}
