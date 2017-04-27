<?php

namespace Tests;

use App\Magic;
use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    // __construct()
    // __destruct()
    public function testConstruct()
    {
        $magic = new Magic($attrs = ['foo' => 'bar']);

        $this->assertEquals($attrs, $magic->getAttributes());
    }

    // __get()
    public function testGet()
    {
        $magic = new Magic(['foo' => 'bar']);

        $this->assertEquals('bar', $magic->foo);
    }

    public function testGetZero()
    {
        $magic = new Magic(['foo' => 0]);

        $this->assertSame(0, $magic->foo);
    }

    public function testGetUndefinedProperty()
    {
        $magic = new Magic;

        $this->assertNull($magic->foo);
    }

    // __set()
    public function testSet()
    {
        $magic = new Magic;

        $magic->foo = 'bar';

        $this->assertContains('bar', $magic->getAttributes());
    }

    // __isset()
    public function testIsset()
    {
        $magic = new Magic(['foo' => 'bar']);

        $this->assertTrue(isset($magic->foo));
        $this->assertFalse(empty($magic->foo));
    }

    // __unset()
    public function testUnset()
    {
        $magic = new Magic(['foo' => 'bar']);

        unset($magic->foo);

        $this->assertNotContains('bar', $magic->getAttributes());
    }

    // __toString()
    public function testToString()
    {
        $magic = new Magic($attrs = ['foo' => 'bar']);

        $this->assertEquals(json_encode($attrs), (string) $magic);
    }

    // __call()
    public function testCall()
    {
        $magic = new Magic($attrs = ['foo' => 'bar']);

        $this->assertEquals('bar', $magic->getFoo());
    }

    public function testCallToUndefinedMethod()
    {
        $magic = new Magic;

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage("Call to undefined method App\\Magic::undefinedMethod()");

        $magic->undefinedMethod();
    }

    // __callStatic()
    public function testCallStatic()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Hello Magic!');

        Magic::undefinedMethod();
    }
}
