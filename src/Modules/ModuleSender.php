<?php


namespace App\Modules;


class ModuleSender implements GrrModuleSenderInterface
{
    /**
     * @var GrrModuleInterface[]
     */
    public $modules = [];
    /**
     * @var iterable
     */
    private $list;

    public function __construct(iterable $list)
    {
        $this->list = $list;
    }

    /**
     * @param GrrModuleInterface $module
     */
    public function addModule(GrrModuleInterface $module)
    {
        $this->modules[] = $module;
    }

    public function postContent()
    {
        foreach ($this->list as $list) {
            $list->postContent();
        }

        foreach ($this->modules as $module) {
            $module->postContent();
        }
    }
}