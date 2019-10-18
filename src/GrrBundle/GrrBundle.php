<?php

namespace App\GrrBundle;

use App\DependencyInjection\Compiler\ModulesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GrrBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ModulesPass());
    }
}
