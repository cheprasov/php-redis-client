<?php

namespace RedisClient\Command\Response;

class ResponseParser {

    const PARSE_ASSOC_ARRAY = 1;
    const PARSE_INTEGER     = 2;
    const PARSE_TIME        = 3;

    /**
     * @param int $type
     * @param mixed $response
     * @return mixed
     */
    public static function parse($type, $response) {
        switch ($type) {
            case self::PARSE_ASSOC_ARRAY:
                return self::parseAssocArray($response);
            case self::PARSE_INTEGER:
                return self::parseInteger($response);
            case self::PARSE_TIME:
                return self::parseTime($response);
            default:
                return $response;
        }
    }

    /**
     * @param string[] $response
     * @return array
     */
    public static function parseAssocArray($response) {
        $array = [];
        for ($i = 0, $count = count($response); $i < $count; $i += 2) {
            $array[$response[$i]] = $response[$i + 1];
        }
        return $array;
    }

    /**
     * @param string $response
     * @return int
     */
    public static function parseInteger($response) {
        return (int) $response;
    }

    /**
     * @param array $response
     * @return string string
     */
    public static function parseTime($response) {
        if (is_array($response) && count($response) === 2) {
            return implode('.', $response);
        }
        return $response;
    }

}
