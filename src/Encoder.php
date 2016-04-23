<?php

namespace Rmtram\JsonpParser;

/**
 * Class Encoder
 * @package Rmtram\JsonpParser
 */
class Encoder
{
    use Settable;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var int
     */
    private $depth = 512;

    /**
     * @var int
     */
    private $option = JSON_ERROR_NONE;

    /**
     * @var string
     */
    private $callback = 'callback';

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $callback
     * @return $this
     */
    public function callback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @return bool|string
     */
    public function encode()
    {
        if (!$this->prohibit()) {
            if (version_compare(PHP_VERSION, '5.5.0', '<')) {
                $json = json_encode($this->value, $this->option);
            } else {
                $json = json_encode($this->value, $this->option, $this->depth);
            }
            if (false === $json) {
                return false;
            }
            return sprintf('%s(%s)', $this->callback, $json);
        }
        return false;
    }

    /**
     * @return bool
     */
    private function prohibit()
    {
        return is_null($this->value);
    }
}