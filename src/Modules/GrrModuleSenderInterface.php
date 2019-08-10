<?php

namespace App\Modules;

interface GrrModuleSenderInterface
{
    public function addModule(GrrModuleInterface $grrModule);

    public function postContent();
}
