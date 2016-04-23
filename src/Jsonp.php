<?php

namespace Rmtram\JsonpParser;

/**
 * Class Jsonp
 * @package Rmtram\JsonpParser
 */
class Jsonp
{
    use Trimmable;

    /**
     * @param mixed $value
     * @return Encoder
     */
    public function encoder($value)
    {
        return new Encoder($value);
    }

    /**
     * @param string $jsonp
     * @return Decoder
     */
    public function decoder($jsonp)
    {
        return new Decoder($jsonp);
    }

    /**
     * @param string $jsonp
     * @param boolean $trimEOL
     * @param boolean $strict
     * @return boolean
     */
    public function is($jsonp, $trimEOL = false, $strict = true)
    {
        if (true === $trimEOL) {
            $jsonp = $this->stripEOL($jsonp);
        }
        if (!preg_match(Decoder::PATTERN, $jsonp)) {
            return false;
        }
        if (false === $strict) {
            return true;
        }
        $jsonp = preg_replace(Decoder::PATTERN, '$1', $jsonp);
        return !!json_decode($jsonp);
    }

}