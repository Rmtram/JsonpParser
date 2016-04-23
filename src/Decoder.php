<?php

namespace Rmtram\JsonpParser;

/**
 * Class Encoder
 * @package Rmtram\JsonpParser
 */
class Decoder
{
    use Settable;

    use Trimmable;

    /**
     * return type to array
     */
    const VARIABLE_TYPE_ARRAY  = true;

    /**
     * return type to object
     */
    const VARIABLE_TYPE_OBJECT = false;

    /**
     * regex pattern.
     */
    const PATTERN = '/^[a-zA-Z0-9._]+\((.+?)\)$/';

    /**
     * @var string
     */
    private $jsonp;

    /**
     * @var int
     */
    private $depth = 512;

    /**
     * @var int
     */
    private $option = JSON_ERROR_NONE;

    /**
     * @param string $jsonp
     */
    public function __construct($jsonp)
    {
        $this->jsonp = $jsonp;
    }

    /**
     * delete CR_LF, CR, LF
     * @return $this
     */
    public function trimEOL()
    {
        if (!$this->prohibit()) {
            $this->jsonp = $this->stripEOL($this->jsonp);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function toArray()
    {
        return $this->parse(self::VARIABLE_TYPE_ARRAY);
    }

    /**
     * @return object|null
     */
    public function toObject()
    {
        return $this->parse(self::VARIABLE_TYPE_OBJECT);
    }

    /**
     * @param boolean $type
     * @return mixed|null
     */
    private function parse($type)
    {
        if (!$this->prohibit()) {
            $json = preg_replace(self::PATTERN, '$1', $this->jsonp);
            return json_decode($json, $type, $this->depth, $this->option);
        }
        return null;
    }

    /**
     * @return bool
     */
    private function prohibit()
    {
        return empty($this->jsonp)
            || !is_string($this->jsonp);
    }
}