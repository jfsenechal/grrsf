<?php


namespace App\Modules;


class ModuleSender implements GrrModuleSenderInterface
{
    /**
     * @var GrrModuleInterface[]
     */
    public $modules = [];

    /**
     * @param GrrModuleInterface $module
     */
    public function addModule(GrrModuleInterface $module)
    {
        $this->modules[] = $module;
    }

    public function postContent()
    {
        dump($this->modules);
        foreach ($this->modules as $module) {
            $module->postContent();
        }
    }
}