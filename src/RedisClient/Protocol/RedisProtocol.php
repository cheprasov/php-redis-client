<?php

namespace RedisClient\Protocol;

use RedisClient\Connection\ConnectionInterface;
use RedisClient\Exception\EmptyResponseException;
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
            case 'boolean':
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
     * @throws UnknownTypeException
     * @throws EmptyResponseException
     */
    protected function read() {

        if (!$line = $this->Connection->readLine()) {
            //todo: timeout usleep
            if (!$line = $this->Connection->readLine()) {
                throw new EmptyResponseException();
            }
        }

        $type = $line[0];
        $data = substr($line, 1, -2);

        switch ($type) {
            case static::TYPE_SIMPLE_STRINGS:
                if ($data === 'OK') {
                    return true;
                }
                return $data;

            case static::TYPE_ERRORS:
                return new ErrorResponseException($data);

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

            default:
                throw new UnknownTypeException(
                    sprintf('Unknown protocol type %s', $type)
                );
        }
    }

    /**
     * @inheritdoc
     */
    public function send($structures, $multi = false) {
        if (!$multi) {
            $raw = $this->pack($structures);
            //echo str_replace(['\n','\r'], '-', json_encode($raw))." => ";
            $this->write($raw);
            $response = $this->read();
        } else {
            $args = func_get_args();
            $raw = '';
            foreach ($structures as $structure) {
                $raw .= $this->pack($structure);
            }
            $this->write($raw);
            $response = [];
            for ($i = count($structures); $i > 0; $i--) {
                $response[] = $this->read();
            }
        }
        return $response;
    }

}
