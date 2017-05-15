<?php

namespace Tests;

use App\Magic;
use App\Amazing;
use App\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testMake()
    {
        $amazing = Container::make(Amazing::class);

        $this->assertEquals('Sasaya', $amazing->getName());
    }

    public function testMakeWithArgs()
    {
        $name = 'John Doe';

        $amazing = Container::make(Amazing::class, compact('name'));

        $this->assertEquals($name, $amazing->getName());
    }

    public function testAutoMake()
    {
        $amazing = Container::make(Amazing::class);

        $this->assertEquals('Sasaya', $amazing->getName());
        $this->assertInstanceOf(Magic::class, $amazing->getMagic());
    }

    public function testCall()
    {
        $this->assertEquals('Sasaya', Container::call('App\Amazing@getName'));
    }

    public function testCallWithArgs()
    {
        $name = 'John Doe';

        $this->assertEquals($name, Container::call('App\Amazing@getName', compact('name')));
    }
}
