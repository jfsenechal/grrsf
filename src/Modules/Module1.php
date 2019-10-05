<?php

namespace App\Modules;

class Module1 implements GrrModuleInterface
{
    public function getName(): string
    {
        return 'module1';
    }

    public function getVersion(): string
    {
        return '1.0';
    }

    public function doSomething()
    {
        echo 'Module 1';
    }
}
