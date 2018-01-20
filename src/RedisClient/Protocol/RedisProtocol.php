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
namespace RedisClient\Protocol;

use RedisClient\Connection\ConnectionInterface;
use RedisClient\Exception\EmptyResponseException;
use RedisClient\Exception\ExceptionFactory;
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
        $this->setConnection($Connection);
    }

    /**
     * @param ConnectionInterface $Connection
     */
    public function setConnection(ConnectionInterface $Connection) {
        $this->Connection = $Connection;
    }

    /**
     * @return ConnectionInterface $Connection
     */
    public function getConnection() {
        return $this->Connection;
    }

    /**
     * @param mixed $data
     * @return string|string[]
     * @throws UnknownTypeException
     */
    protected function pack($data) {
        if (is_string($data) || is_int($data) || is_bool($data) || is_float($data) || is_null($data)) {
            return $this->packProtocolBulkString((string)$data);
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
     * @param string $raw
     * @return null|int
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
            throw new EmptyResponseException('Empty response. Please, check connection timeout.');
        }

        $type = $line[0];
        $data = substr($line, 1, -2);

        if ($type === self::TYPE_BULK_STRINGS) {
            $length = (int)$data;
            if ($length === -1) {
                return null;
            }
            $read = $this->Connection->read($length + 2);
            if (is_null($read)) {
                throw new EmptyResponseException('Can not read response. Please, check connection timeout.');
            }
            return substr($read, 0, -2);
        }

        if ($type === self::TYPE_SIMPLE_STRINGS) {
            if ($data === 'OK') {
                return true;
            }
            return $data;
        }

        if ($type === self::TYPE_INTEGERS) {
            return (int)$data;
        }

        if ($type === self::TYPE_ARRAYS) {
            $count = (int)$data;
            if ($count === -1) {
                return null;
            }
            $array = [];
            for ($i = 0; $i < $count; ++$i) {
                $array[] = $this->read();
            }
            return $array;
        }

        if ($type === self::TYPE_ERRORS) {
            return ExceptionFactory::createResponseExceptionByMessage($data);
        }

        throw new UnknownTypeException('Unknown protocol type '. $type);
    }

    /**
     * @inheritdoc
     */
    public function sendRaw($command) {
        $this->write($command);
        return $response = $this->read();
    }

    /**
     * @inheritdoc
     */
    public function send(array $structures) {
        return $this->sendRaw($this->packProtocolArray($structures));
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
        for ($i = count($structures); $i > 0; --$i) {
            $response[] = $this->read();
        }
        return $response;
    }

    /**
     * @inheritdoc
     */
    public function subscribe(array $structures, $callback) {
        $this->write($this->packProtocolArray($structures));
        do {
            try {
                $response = (array)$this->read();
                for ($i = count($response); $i < 4; ++$i) {
                    $response[] = null;
                }
            } catch (EmptyResponseException $Ex) {
                $response = [null, null, null, null];
            }
            $continue = call_user_func_array($callback, $response);
        } while ($continue);
    }

}
