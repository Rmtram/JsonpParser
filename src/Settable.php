<?php

namespace Rmtram\JsonpParser;

/**
 * Class Settable
 * @package Rmtram\JsonpParser
 */
trait Settable
{
    /**
     * @param int $depth
     * @return $this
     */
    public function depth($depth)
    {
        if (is_int($depth)) {
            $this->depth = $depth;
        }
        return $this;
    }

    /**
     * @param int $option
     * @return $this
     */
    public function option($option)
    {
        if (is_int($option)) {
            $this->option = $option;
        }
        return $this;
    }
}