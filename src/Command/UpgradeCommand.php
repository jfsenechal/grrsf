<?php

namespace App\Command;

use App\Repository\AreaRepository;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpgradeCommand extends Command
{
    protected static $defaultName = 'grr:upgrade';
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var SymfonyStyle
     */
    private $io;
    /**
     * @var LoaderInterface
     */
    private $loader;

    public function __construct(
        ?string $name = null,
        AreaRepository $areaRepository,
        LoaderInterface $loader
    ) {
        parent::__construct($name);
        $this->areaRepository = $areaRepository;
        $this->loader = $loader;
    }

    protected function configure()
    {
        $this
            ->setDescription('Migration des données');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->areaUpgrade();
    }

    protected function areaUpgrade()
    {
        $path = __DIR__.'/../';

        $files = [
            $path.'DataFixtures/users.yaml',
            $path.'DataFixtures/entry_type.yaml',
            $path.'DataFixtures/area.yaml',
            $path.'DataFixtures/room.yaml',
            $path.'DataFixtures/entry_today.yaml',
          //  $path.'DataFixtures/periodicity.yaml',
        //    $path.'DataFixtures/entry_with_periodicity.yaml',
         //   $path.'DataFixtures/periodicity_day.yaml',
        ];

        $this->loader->load($files);
    }
}
