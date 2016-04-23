<?php

namespace Rmtram\JsonpParser\TestCase;

use Rmtram\JsonpParser\Jsonp;

/**
 * Class DecodeTest
 * @package Rmtram\JsonpParserTestCase
 */
class DecodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * data pattern.
     * @var array
     */
    private $pattern = [
        'dictionary' => '{"name":"example"}',
        'list'       => '["a","b","c"]',
        'string'     => '"a"',
        'int'        => '1',
        'multiple'   => '[{"name":"ex1"},{"name":"ex2"}]',
        'eol'        => "{\"name\":\n\"example\"}",
        'irregular'  => '["callback([1])"]',
        'prohibit'   => "{name:'example'}",
        'null'       => null,
        'empty'      => '',
        'bigint'     => 1000000000000000000000000000000000000000000000000000
    ];

    /**
     * @param $pattern
     * @param string $callbackName
     * @return string
     */
    private function mock($pattern, $callbackName = 'callback')
    {
        return sprintf('%s(%s)', $callbackName, $this->pattern[$pattern]);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDepth()
    {
        if (!version_compare(PHP_VERSION, '5.5.0', '<')) {
            $jsonp = new Jsonp();
            $mock = $this->mock('multiple');
            $actual = $jsonp
                ->decoder($mock)
                ->depth(1)
                ->toObject();
            $this->assertNull($actual);
        }
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeByEmpty()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('empty');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertEquals('', $actual);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeByNull()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('null');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertNull($actual);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeChangeCallbackName()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('string', '__.Example');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertInternalType('string', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('a', $actual[0]);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeFromProhibitToObject()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('prohibit');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertNull($actual);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeFromIrregularToObject()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('irregular');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertInternalType('array', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('callback([1])', $actual[0]);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::trimEOL
     * @covers Decoder::toObject
     */
    public function testDecodeFromTrimEolToObject()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('eol');
        $actual = $jsonp->decoder($mock)
            ->trimEOL()
            ->toObject();
        $this->assertInternalType('object', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('example', $actual->name);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeFromEolToObject()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('eol');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertNull($actual);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeFromMultipleToObject()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('multiple');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertNotEmpty($actual);
        $this->assertInternalType('array', $actual);
        $this->assertInternalType('object', $actual[0]);
        $this->assertInternalType('object', $actual[1]);
        $this->assertEquals('ex1', $actual[0]->name);
        $this->assertEquals('ex2', $actual[1]->name);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeFromDictionaryToObject()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('dictionary');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertInternalType('object', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('example', $actual->name);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeFromListToObject()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('list');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertInternalType('array', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('a', $actual[0]);
        $this->assertEquals('b', $actual[1]);
        $this->assertEquals('c', $actual[2]);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeFromStringToObject()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('string');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertInternalType('string', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('a', $actual);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDecodeFromIntegerToObject()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('int');
        $actual = $jsonp->decoder($mock)->toObject();
        $this->assertInternalType('int', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals(1, $actual);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toArray
     */
    public function testDecodeFromProhibitToArray()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('prohibit');
        $actual = $jsonp->decoder($mock)->toArray();
        $this->assertNull($actual);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toArray
     */
    public function testDecodeFromIrregularToArray()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('irregular');
        $actual = $jsonp->decoder($mock)->toArray();
        $this->assertInternalType('array', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('callback([1])', $actual[0]);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::trimEOL
     * @covers Decoder::toArray
     */
    public function testDecodeFromTrimEolToArray()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('eol');
        $actual = $jsonp->decoder($mock)
            ->trimEOL()
            ->toArray();
        $this->assertInternalType('array', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('example', $actual['name']);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toArray
     */
    public function testDecodeFromEolToArray()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('eol');
        $actual = $jsonp->decoder($mock)->toArray();
        $this->assertNull($actual);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toArray
     */
    public function testDecodeFromMultipleToArray()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('multiple');
        $actual = $jsonp->decoder($mock)->toArray();
        $this->assertInternalType('array', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('ex1', $actual[0]['name']);
        $this->assertEquals('ex2', $actual[1]['name']);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toArray
     */
    public function testDecodeFromDictionaryToArray()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('dictionary');
        $actual = $jsonp->decoder($mock)->toArray();
        $this->assertInternalType('array', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('example', $actual['name']);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toArray
     */
    public function testDecodeFromListToArray()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('list');
        $actual = $jsonp->decoder($mock)->toArray();
        $this->assertInternalType('array', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('a', $actual[0]);
        $this->assertEquals('b', $actual[1]);
        $this->assertEquals('c', $actual[2]);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toArray
     */
    public function testDecodeFromStringToArray()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('string');
        $actual = $jsonp->decoder($mock)->toArray();
        $this->assertInternalType('string', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals('a', $actual);
    }

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toArray
     */
    public function testDecodeFromIntegerToArray()
    {
        $jsonp = new Jsonp();
        $mock = $this->mock('int');
        $actual = $jsonp->decoder($mock)->toArray();
        $this->assertInternalType('int', $actual);
        $this->assertNotEmpty($actual);
        $this->assertEquals(1, $actual);
    }

}