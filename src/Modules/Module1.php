<?php

namespace App\Modules;

class Module1 implements GrrModuleInterface
{
    public function getSupport(): string
    {
        return 'module1';
    }

    public function postContent()
    {
        var_dump('module1');
    }
}
