<?php


namespace App\GrrBundle\DependencyInjection;

use App\Modules\GrrModuleInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class GrrExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(GrrModuleInterface::class)
            ->addTag('grr_module');

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['SecurityBundle'])) {
            $this->loadDefaultValuesVich($container);
        }
    }

    protected function loadDefaultValuesVich(ContainerBuilder $container)
    {
        $configs = $this->loadYml('security.yaml');
        foreach ($container->getExtensions() as $name => $extension) {
            if ($name === 'security') {
                $container->prependExtensionConfig($name, $configs);
                break;
            }
        }
    }

    protected function loadYml($name)
    {
        try {
            return Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/'.$name));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }

        return [];
    }
}