<?php

namespace Rmtram\JsonpParser\TestCase;

use Rmtram\JsonpParser\Jsonp;

/**
 * Class DecodeTest
 * @package Rmtram\JsonpParserTestCase
 */
class EncodeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Jsonp::decoder
     * @covers Decoder::toObject
     */
    public function testDepth()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp
            ->encoder([['name' => 'example']])
            ->depth(1)
            ->encode();
        if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
            // PHP 5.5 over
            $this->assertFalse($actual);
        } else {
            $this->assertNotNull($actual);
        }
    }

    /**
     * @covers Jsonp::encoder
     * @covers Encoder::callback
     * @covers Encoder::encode
     */
    public function testEncodeChangeCallbackName()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->encoder([1, 2, 3])
            ->callback('_.Example')
            ->encode();
        $this->assertEquals('_.Example([1,2,3])', $actual);
    }

    /**
     * @covers Jsonp::encoder
     * @covers Encoder::encode
     */
    public function testEncodeFromArray()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->encoder([1, 2, 3])->encode();
        $this->assertEquals('callback([1,2,3])', $actual);
    }

    /**
     * @covers Jsonp::encoder
     * @covers Encoder::encode
     */
    public function testEncodeFromString()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->encoder('example')->encode();
        $this->assertEquals('callback("example")', $actual);
    }

    /**
     * @covers Jsonp::encoder
     * @covers Encoder::encode
     */
    public function testEncodeFromEmptyString()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->encoder('')->encode();
        $this->assertEquals('callback("")', $actual);
    }

    /**
     * @covers Jsonp::encoder
     * @covers Encoder::encode
     */
    public function testEncodeFromObject()
    {
        $jsonp = new Jsonp();
        $object = new \stdClass();
        $object->name = 'example';
        $actual = $jsonp->encoder($object)->encode();
        $this->assertEquals('callback({"name":"example"})', $actual);
    }

    /**
     * @covers Jsonp::encoder
     * @covers Encoder::encode
     */
    public function testEncodeFromInteger()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->encoder(1)->encode();
        $this->assertEquals('callback(1)', $actual);
    }

    /**
     * @covers Jsonp::encoder
     * @covers Encoder::encode
     */
    public function testEncodeFromNull()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->encoder(null)->encode();
        $this->assertFalse($actual);
    }

}