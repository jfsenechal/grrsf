<?php

namespace App\Modules;

class Module2 implements GrrModuleInterface
{
    public function getSupport(): string
    {
        return 'module2';
    }

    public function postContent()
    {
        var_dump('module2');
    }
}
