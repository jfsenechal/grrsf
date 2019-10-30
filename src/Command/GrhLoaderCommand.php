<?php

namespace App\Command;

use Fidry\AliceDataFixtures\Loader\PersisterLoader;
use Fidry\AliceDataFixtures\Loader\SimpleLoader;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GrhLoaderCommand extends Command
{
    protected static $defaultName = 'grr:loader';
    /**
     * @var PersisterLoader
     */
    private $loader;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->loader = $container->get('fidry_alice_data_fixtures.loader.doctrine');
    }

    protected function configure()
    {
        $this
            ->setDescription('Test load fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $basePath = __DIR__.'/../Fixtures/';

        $this->loader->load([$basePath.'area.yaml']);

    }
}
