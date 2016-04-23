<?php

namespace Rmtram\JsonpParser;

/**
 * Class Trimmable
 * @package Rmtram\JsonpParser
 */
trait Trimmable
{
    /**
     * @param string $value
     * @return string
     */
    private function stripEOL($value)
    {
        return str_replace(["\r\n", "\r", "\n"], '', $value);
    }
}