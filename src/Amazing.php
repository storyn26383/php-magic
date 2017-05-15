<?php

namespace App;

class Amazing
{
    protected $name;

    protected $magic;

    public function __construct($name = 'Sasaya', Magic $magic = null)
    {
        $this->name = $name;
        $this->magic = $magic;
    }

    public function getName($name = null)
    {
        return $name ?? $this->name;
    }

    public function getMagic()
    {
        return $this->magic;
    }
}
