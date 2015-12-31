<?php

namespace RedisClient\Command\Parameter;

use InvalidArgumentException;
use RedisClient\Command\Command;

class Parameter {

    /**
     * @param string|string[]|array $param Example: '127.0.0.1:6379' or ['127.0.0.1', '6379'] or ['ip'=>'127.0.0.1', 'port'=>'6379']
     * @return string[]
     * @throws InvalidArgumentException;
     */
    public static function address($param) {
        if ($param && is_string($param)) {
            $param = explode(':', trim($param), 2);
        }
        if (is_array($param)) {
            if (isset($param[0]) && isset($param[1])) {
                return [
                    static::string($param[0]),
                    static::port($param[1]),
                ];
            } elseif (isset($param['ip']) && isset($param['port'])) {
                return [
                    static::string($param['ip']),
                    static::port($param['port']),
                ];
            }
        }
        throw new InvalidArgumentException('Invalid address '. $param);
    }

    /**
     * @var string[]
     */
    protected static $aggregateParams = ['SUM', 'MIN', 'MAX'];

    /**
     * @param string $param
     * @return string
     * @throws InvalidArgumentException
     */
    public static function aggregate($param) {
        $param = strtoupper((string) $param);
        if (in_array($param, static::$aggregateParams)) {
            return $param;
        }
        throw new InvalidArgumentException('Invalid aggregate '. $param);
    }

    /**
     * @param array $array
     * @param bool $flip
     * @return string[]
     * @throws InvalidArgumentException
     */
    public static function assocArray(array $array, $flip = false) {
        $flip = (bool) $flip;
        $structure = [];
        foreach ($array as $key => $value) {
            $value = static::string($value);
            $flip && array_push($structure, $value, $key) || array_push($structure, $key, $value);
        }
        return $structure;
    }

    /**
     * @var string[]
     */
    protected static $bitOperationParams = ['AND', 'OR', 'XOR', 'NOT'];

    /**
     * @param string $operation
     * @return string
     * @return InvalidArgumentException
     */
    public static function bitOperation($operation) {
        $operation = strtoupper((string) $operation);
        if (in_array($operation, static::$bitOperationParams)) {
            return $operation;
        }
        throw new InvalidArgumentException('Invalid bit operator '. $operation);
    }

    /**
     * @param int|bool $bit
     * @return int
     */
    public static function bit($bit) {
        return (int) (bool) $bit;
    }

    /**
     * @param Command $Command
     * @return string[]
     */
    public static function command(Command $Command) {
        return $Command->getStructure();
    }

    /**
     * @param int|float|string $param
     * @param int[]|string[] $enum
     * @return string
     */
    public static function enum($param, $enum) {
        if (!is_numeric($param) && !is_string($param)) {
            throw new InvalidArgumentException($param);
        }
        if (!is_array($enum)) {
            throw new InvalidArgumentException($enum);
        }
        if (is_string($param)) {
            $param = strtoupper($param);
        }
        if (!in_array($param, $enum)) {
            throw new InvalidArgumentException('Invalid enum '. $param);
        }
        return (string) $param;
    }

    /**
     * @param int|float|string $float
     * @return float
     */
    public static function float($float) {
        return (float) $float;
    }

    /**
     * @param int|float|string $int
     * @return int
     */
    public static function integer($int) {
        return (int) $int;
    }

    /**
     * @param mixed
     * @return int[]
     */
    public static function integers($integers) {
        $integers = (array) $integers;
        return array_map('static::integer', $integers);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function key($key) {
        return (string) $key;
    }

    /**
     * @param string|string[] $keys
     * @return array
     */
    public static function keys($keys) {
        $keys = (array) $keys;
        return array_map('static::key', $keys);
    }

    /**
     * @param int|string|int[]|string[] $limit
     * @return int[]
     */
    public static function limit($limit) {
        if (is_numeric($limit)) {
            return [0, (int) $limit];
        }
        if (is_array($limit) && isset($limit['count'])) {
            return [
                empty($limit['offset']) ? 0: (int) $limit['offset'],
                (int) $limit['count'],
            ];
        }
        if ($limit && is_string($limit) && preg_match('/^-?\d+\s+-?\d+$/', $limit)) {
            $limit = preg_split('/\s+/', trim($limit), 2);
        }
        if (is_array($limit)) {
            if (isset($limit[0]) && isset($limit[1])) {
                return [(int) $limit[0], (int) $limit[1]];
            }
            if (isset($limit[0]) && !isset($limit[1])) {
                return [0, (int) $limit[0]];
            }
        }
        throw new InvalidArgumentException('Invalid limit '. $limit);
    }

    const MIN_MAX_PREG = '/^([-+]inf|\(?-?\d+)$/';

    /**
     * @param int|string $param
     * @return int|string
     */
    public static function minMax($param) {
        $param = trim($param);
        if (preg_match(static::MIN_MAX_PREG, $param)) {
            return $param;
        }
        throw new InvalidArgumentException('Invalid param '.$param);
    }

    /**
     * @var string[]
     */
    protected static $nxXxParams = ['NX', 'XX'];

    /**
     * @param string $param
     * @return string
     */
    public static function nxXx($param) {
        $param = strtoupper((string) $param);
        if (in_array($param, static::$nxXxParams)) {
            return $param;
        }
        throw new InvalidArgumentException('Invalid param '. $param);
    }

    /**
     * @param int|float|string $int
     * @return int
     */
    public static function port($int) {
        $int = (int) $int;
        if ($int > 0 && $int <= 65535) {
            return $int;
        }
        throw new InvalidArgumentException('Port number must be more than 0 and less than or equal 65535');
    }

    const SPECIFY_INTERVAL_PREG = '/^(-|\+|[\(\[]\w+)$/';

    /**
     * @param string|int $param
     * @return string
     */
    public static function specifyInterval($param) {
        $param = trim($param);
        if (preg_match(static::SPECIFY_INTERVAL_PREG, $param)) {
            return $param;
        }
        throw new InvalidArgumentException('Invalid specify interval '. $param);
    }

    /**
     * @param string $string
     * @return string
     */
    public static function string($string) {
        return (string) $string;
    }

    /**
     * @param string|string[] $strings
     * @return array
     */
    public static function strings($strings) {
        $strings = (array) $strings;
        return array_map('static::string', $strings);
    }

}
