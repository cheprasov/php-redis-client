<?php

namespace RedisClient\Protocol;

use RedisClient\Connection\ConnectionInterface;
use RedisClient\Exception\ErrorException;
use RedisClient\Exception\ErrorResponseException;
use RedisClient\Exception\UnknownTypeException;

class RedisProtocol implements ProtocolInterface {

    const EOL = "\r\n";

    const TYPE_SIMPLE_STRINGS = '+';
    const TYPE_ERRORS = '-';
    const TYPE_INTEGERS = ':';
    const TYPE_BULK_STRINGS = '$';
    const TYPE_ARRAYS = '*';

    /**
     * @var ConnectionInterface
     */
    protected $Connection;

    /**
     * @param ConnectionInterface $Connection
     */
    public function __construct(ConnectionInterface $Connection) {
        $this->Connection = $Connection;
    }

    /**
     * @param mixed $data
     * @return string|string[]
     * @throws UnknownTypeException
     */
    protected function pack($data) {
        switch ($type = gettype($data)) {
            case 'array':
                return $this->packProtocolArray($data);
            case 'string':
            case 'double':
            case 'integer':
                return $this->packProtocolBulkString($data);
            case 'NULL':
                return $this->packProtocolNull();
            default:
                throw new UnknownTypeException($type);
        }
    }

    /**
     * @param array $array
     * @return string
     */
    protected function packProtocolArray($array) {
        $pack = [static::TYPE_ARRAYS . count($array) . static::EOL];
        foreach ($array as $a) {
            $pack[] = $this->pack($a);
        }
        return implode('', $pack);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function packProtocolBulkString($string) {
        return static::TYPE_BULK_STRINGS . strlen($string) . static::EOL . $string . static::EOL;
    }

    /**
     * @return string
     */
    protected function packProtocolNull() {
        return static::TYPE_BULK_STRINGS . '-1' . static::EOL;
    }

    /**
     * @param $raw
     * @return null|string
     */
    protected function write($raw) {
        return $this->Connection->write($raw);
    }

    /**
     * @return array|int|null|string
     * @throws \Exception
     */
    protected function read() {
        $line = $this->Connection->readLine();

        if ($line === false || $line === '') {
            throw new ErrorException();
        }

        $prefix = $line[0];
        $data = substr($line, 1, -2);

        switch ($prefix) {
            case static::TYPE_SIMPLE_STRINGS:
                if ($data === 'OK') {
                    return true;
                }
                return $data;

            case static::TYPE_ERRORS:
                //$errs = explode(" ", $data, 2);
                throw new ErrorResponseException($data);
                break;

            case static::TYPE_INTEGERS:
                return (int) $data;

            case static::TYPE_BULK_STRINGS:
                $length = (int) $data;
                if ($length === -1) {
                    return null;
                }
                $data = substr($this->Connection->read($length + 2), 0, -2);
                return $data;

            case static::TYPE_ARRAYS:
                $count = (int) $data;
                if ($count === -1) {
                    return null;
                }
                $array = [];
                for ($i = 0; $i < $count; $i++) {
                    $array[] = $this->read();
                }
            return $array;
        }

    }

    /**
     * @param array $structure
     * @return mixed
     * @throws \Exception
     */
    public function send($structure) {
        if (func_num_args() === 1) {
            $raw = $this->pack($structure);
            var_dump($raw);
            $this->write($raw);
            $response = $this->read();
        } else {
            $args = func_get_args();
            $raw = '';
            foreach ($args as $arg) {
                $raw .= $this->pack($arg);
            }
            $this->write($raw);
            $response = [];
            for ($i = count($args); $i > 0; $i--) {
                $response[] = $this->read();
            }
        }
        return $response;
    }

}
