<?php

namespace Tests;

use App\Magic;
use App\Amazing;
use PHPUnit\Framework\TestCase;

class AmazingTest extends TestCase
{
    public function testGetName()
    {
        $amazing = new Amazing($name = 'John Doe');

        $this->assertEquals($name, $amazing->getName());
    }

    public function testGetNameWithSpecifyName()
    {
        $amazing = new Amazing('John Doe');

        $this->assertEquals($name = 'Sasaya', $amazing->getName($name));
    }

    public function testGetMagic()
    {
        $amazing = new Amazing('John Doe', $magic = new Magic);

        $this->assertEquals($magic, $amazing->getMagic());
    }
}
