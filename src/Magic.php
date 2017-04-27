<?php

namespace App;

use BadMethodCallException;

class Magic
{
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

        $className = static::class;

        throw new BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }

    public static function __callStatic($method, $args)
    {
        throw new BadMethodCallException('Hello Magic!');
    }
}
