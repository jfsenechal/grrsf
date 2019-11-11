<?php

namespace App\Modules;

class ModuleSender implements GrrModuleSenderInterface
{
    /**
     * @var GrrModuleInterface[]
     */
    public $modules = [];

    public function addModule(GrrModuleInterface $module): void
    {
        $this->modules[] = $module;
    }

    public function postContent(): void
    {
        foreach ($this->modules as $module) {
            $module->doSomething();
        }
    }
}
