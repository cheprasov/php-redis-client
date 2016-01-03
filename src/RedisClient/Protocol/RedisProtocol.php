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
        if (is_string($data) || is_int($data) || is_bool($data) || is_float($data)) {
            return $this->packProtocolBulkString((string) $data);
        }
        if (is_null($data)) {
            return $this->packProtocolNull();
        }
        if (is_array($data)) {
            return $this->packProtocolArray($data);
        }
        throw new UnknownTypeException(gettype($data));
    }

    /**
     * @param array $array
     * @return string
     */
    protected function packProtocolArray($array) {
        $pack = self::TYPE_ARRAYS . count($array) . self::EOL;
        foreach ($array as $a) {
            $pack .= $this->pack($a);
        }
        return $pack;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function packProtocolBulkString($string) {
        return self::TYPE_BULK_STRINGS . strlen($string) . self::EOL . $string . self::EOL;
    }

    /**
     * @return string
     */
    protected function packProtocolNull() {
        return self::TYPE_BULK_STRINGS . '-1' . self::EOL;
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

        if ($type === self::TYPE_BULK_STRINGS) {
            $length = (int) $data;
            if ($length === -1) {
                return null;
            }
            return substr($this->Connection->read($length + 2), 0, -2);
        }

        if ($type === self::TYPE_SIMPLE_STRINGS) {
            if ($data === 'OK') {
                return true;
            }
            return $data;
        }

        if ($type === self::TYPE_INTEGERS) {
            return (int) $data;
        }

        if ($type === self::TYPE_ARRAYS) {
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

        if ($type === self::TYPE_ERRORS) {
            return new ErrorResponseException($data);
        }

        throw new UnknownTypeException(sprintf('Unknown protocol type %s', $type));
    }


    /**
     * @inheritdoc
     */
    public function send(array $structures) {
        //echo str_replace(['\n','\r'], '-', json_encode($raw))." => ";
        $this->write($this->packProtocolArray($structures));
        return $response = $this->read();
    }

    /**
     * @inheritdoc
     */
    public function sendMulti(array $structures) {
        $raw = '';
        foreach ($structures as $structure) {
            $raw .= $this->pack($structure);
        }
        $this->write($raw);
        $response = [];
        for ($i = count($structures); $i > 0; $i--) {
            $response[] = $this->read();
        }
        return $response;
    }

}
