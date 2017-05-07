<?php

namespace Tests;

use App\Magic;
use Exception;
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
        $this->expectExceptionMessage("Call to undefined method App\\Magic::undefinedMethod()");

        Magic::undefinedMethod();
    }

    // __sleep
    // __wakeup
    public function testSleep()
    {
        $magic = new Magic;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Serialization of 'App\\Magic' is not allowed");

        serialize($magic);
    }

    // __clone
    public function testClone()
    {
        $magic = new Magic;
        $cloner = clone $magic;

        $this->assertTrue($cloner->cloner);
    }

    // ArrayAccess
    public function testOffsetGet()
    {
        $magic = new Magic(['foo' => 'bar']);

        $this->assertEquals('bar', $magic['foo']);
    }

    public function testOffsetSet()
    {
        $magic = new Magic;

        $magic['foo'] = 'bar';

        $this->assertEquals('bar', $magic['foo']);
    }

    public function testOffsetUnset()
    {
        $magic = new Magic(['foo' => 'bar']);

        unset($magic['foo']);

        $this->assertNull($magic['foo']);
    }

    public function testOffsetExists()
    {
        $magic = new Magic(['foo' => 'bar']);

        $this->assertTrue(isset($magic['foo']));
        $this->assertFalse(empty($magic['foo']));
    }

    // IteratorAggregate
    public function testIterate()
    {
        $magic = new Magic($attrs = ['foo' => 'bar']);

        foreach ($magic as $key => $value) {
            $this->assertEquals($attrs[$key], $value);
        }
    }

    // JsonSerializable
    public function testJsonSerialize()
    {
        $magic = new Magic($attrs = ['foo' => 'bar']);

        $this->assertEquals(json_encode($attrs), json_encode($magic));
    }

    // __invoke
    public function testGetInvoke()
    {
        $magic = new Magic($attrs = ['foo' => 'bar']);

        $this->assertEquals($attrs, $magic());
    }

    // bindTo
    public function testBindTo()
    {
        $magic = new Magic($attrs = ['foo' => 'bar']);

        $magic->macro('attrs', function () {
            return $this->getAttributes();
        });

        $this->assertEquals($magic->getAttributes(), $magic->attrs());
    }

    // bind
    public function testBind()
    {
        Magic::macro('macros', function () {
            return array_keys(static::$macros);
        });

        $this->assertEquals(['attrs', 'macros'], Magic::macros());
    }
}
