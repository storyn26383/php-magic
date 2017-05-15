<?php

namespace App;

use ReflectionClass;
use ReflectionMethod;

class Container
{
    public static function make($class, $args = [])
    {
        // ReflectionClass
        $reflector = new ReflectionClass($class);

        // ReflectionMethod
        $constructor = $reflector->getConstructor();

        // ReflectionParameter
        $dependencies = $constructor->getParameters();

        return $reflector->newInstanceArgs(static::resolveDependencies($dependencies, $args));
    }

    public static function call($method, $args = [])
    {
        list($class, $method) = explode('@', $method);

        $instance = static::make($class);

        // ReflectionMethod
        $reflector = new ReflectionMethod($class, $method);

        // ReflectionParameter
        $dependencies = $reflector->getParameters();

        return $instance->$method(...static::resolveDependencies($dependencies, $args));
    }

    protected static function resolveDependencies($dependencies, $args = [])
    {
        return array_map(function ($dependency) use ($args) {
            if ($class = $dependency->getClass()) {
                return static::make($class->getName());
            }

            return $args[$dependency->getName()] ?? $dependency->getDefaultValue();
        }, $dependencies);
    }
}
