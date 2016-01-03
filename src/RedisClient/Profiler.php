<?php

namespace RedisClient;

class Profiler {

    /**
     * @var array
     */
    protected static $timers = [];

    /**
     * @var array
     */
    protected static $timerCounters = [];

    /**
     * @var string[]
     */
    protected static $timerNames = [];

    /**
     * @var array
     */
    protected static $counters = [];

    /**
     * @var array
     */
    protected static $workTimers = [];

    /**
     * @param string $name
     */
    public static function start($name) {
        self::$timerNames[] = $name;
        self::$workTimers[$name] = microtime(true);
    }

    /**
     * @param string|null $name
     */
    public static function stop($name = null) {
        $time = microtime(true);
        if (!$name) {
            $name = array_pop(self::$timerNames);
        }
        if (!isset(self::$workTimers[$name])) {
            return;
        }
        if (!isset(self::$timerCounters[$name])) {
            self::$timerCounters[$name] = 1;
            self::$timers[$name] = $time - self::$workTimers[$name];
        } else {
            self::$timerCounters[$name]++;
            self::$timers[$name] += $time - self::$workTimers[$name];
        }
        unset(self::$workTimers[$name]);
    }

    /**
     * @param string $name
     * @param int $incr
     */
    public static function count($name, $incr = 1) {
        if (isset(self::$counters[$name])) {
            self::$counters[$name] += $incr;
        } else {
            self::$counters[$name] = $incr;
        }
    }

    /**
     * @return string[]
     */
    public static function getStat() {
        $result = [];
        foreach (self::$timers as $name => $time) {
            $result[] = sprintf('PROFILER TIMER: %s: %s sec / %s = %s',
                    $name,
                    $time,
                    self::$timerCounters[$name],
                    $time / self::$timerCounters[$name]
                );
        }
        foreach (self::$counters as $name => $count) {
            $result[] = sprintf('PROFILER COUNT: %s: %s', $name, $count);
        }
        return $result;
    }

    /**
     *
     */
    public static function echoStat() {
        echo "\n". implode("\n", self::getStat()) ."\n";
    }
}
