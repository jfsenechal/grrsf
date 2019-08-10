<?php

namespace App\MyBundle;

use App\DependencyInjection\Compiler\ModulesPass;
use App\Modules\GrrModuleInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GrrBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerForAutoconfiguration(GrrModuleInterface::class)
            ->addTag('grr_module');

        $container->addCompilerPass(new ModulesPass());
    }
}
