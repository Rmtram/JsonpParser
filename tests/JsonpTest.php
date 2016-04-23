<?php

namespace Rmtram\JsonpParser\TestCase;

use Rmtram\JsonpParser\Jsonp;

/**
 * Class DecodeTest
 * @package Rmtram\JsonpParserTestCase
 */
class JsonpTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Jsonp::is
     */
    public function testIsJsonpNormalModeWithExactData()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->is('callback([1,2,3])', false, false);
        $this->assertTrue($actual);
    }

    /**
     * @covers Jsonp::is
     */
    public function testIsJsonpNormalModeWithNotParenthesisData()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->is('callback[1,2,3])', false, false);
        $this->assertFalse($actual);
    }

    /**
     * @covers Jsonp::is
     */
    public function testIsJsonpNormalModeWithMultipleParenthesisData()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->is('callback("o")(000)', false, false);
        $this->assertTrue($actual);
    }

    /**
     * @covers Jsonp::is
     */
    public function testIsJsonpNormalModeWithNotDoubleQuotationInString()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->is('callback(example)', false, false);
        $this->assertTrue($actual);
    }

    /**
     * @covers Jsonp::is
     */
    public function testIsJsonpStrictModeWithExactData()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->is('callback([1,2,3])');
        $this->assertTrue($actual);
    }

    /**
     * @covers Jsonp::is
     */
    public function testIsJsonpStrictModeWithNotParenthesisData()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->is('callback[1,2,3])');
        $this->assertFalse($actual);
    }

    /**
     * @covers Jsonp::is
     */
    public function testIsJsonpStrictModeWithMultipleParenthesisData()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->is('callback("o")(000)');
        $this->assertFalse($actual);
    }

    /**
     * @covers Jsonp::is
     */
    public function testIsJsonpStrictModeWithNotDoubleQuotationInString()
    {
        $jsonp = new Jsonp();
        $actual = $jsonp->is('callback(example)');
        $this->assertFalse($actual);
    }

    /**
     * @covers Jsonp::is
     */
    public function testIsJsonpTrimEOL()
    {
        $jsonp = new Jsonp();
        $text = "\ncallback\n([1,2,3])\n";
        $actual = $jsonp->is($text, true);
        $this->assertTrue($actual);
    }

    /**
     * @covers Jsonp::is
     */
    public function testIsJsonpEOL()
    {
        $jsonp = new Jsonp();
        $text = "\ncallback\n([1,2,3])\n";
        $actual = $jsonp->is($text, false);
        $this->assertFalse($actual);
    }

}