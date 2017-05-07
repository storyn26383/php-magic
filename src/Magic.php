<?php

namespace App;

use Closure;
use Exception;
use ArrayAccess;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;
use BadMethodCallException;

class Magic implements ArrayAccess, IteratorAggregate, JsonSerializable
{
    protected static $macros = [];

    public $cloner = false;

    protected $attributes;

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    public function __toString()
    {
        return json_encode($this->getAttributes());
    }

    public function __call($method, $args)
    {
        if (preg_match('/^get([A-Z][A-Za-z0-9_]*)$/', $method, $matches)) {
            return $this->attributes[lcfirst($matches[1])];
        }

        if (isset(static::$macros[$method])) {
            return call_user_func_array(static::$macros[$method]->bindTo($this, static::class), $args);
        }

        $className = static::class;

        throw new BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }

    public static function __callStatic($method, $args)
    {
        if (isset(static::$macros[$method])) {
            return call_user_func_array(Closure::bind(static::$macros[$method], null, static::class), $args);
        }

        $className = static::class;

        throw new BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }

    public function __sleep()
    {
        $className = static::class;

        throw new Exception("Serialization of '{$className}' is not allowed");
    }

    public function __clone()
    {
        $this->cloner = true;
    }

    public function offsetGet($key)
    {
        return $this->__get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->__set($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->__unset($key);
    }

    public function offsetExists($key)
    {
        return $this->__isset($key);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->getAttributes());
    }

    public function jsonSerialize()
    {
        return $this->getAttributes();
    }

    public function __invoke()
    {
        return $this->getAttributes();
    }

    public static function macro($name, Closure $macro)
    {
        static::$macros[$name] = $macro;
    }
}
