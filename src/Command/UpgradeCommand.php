<?php

namespace App\Command;

use App\Repository\AreaRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Fidry\AliceDataFixtures\Loader\PersisterLoader;
use Fidry\AliceDataFixtures\LoaderInterface;
use Nelmio\Alice\Loader\NativeLoader;
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
        $areas = $this->areaRepository->findAll();
        $files = [__DIR__.'/../DataFixtures/users.yaml'];

        $objectSet = $this->loader->load($files);

        /*   foreach ($areas as $area) {
               $displayDays = $area->getDisplayDays();
               if (7 == strlen($displayDays)) {
                   var_dump(str_split($displayDays));
               } else {
                   $this->io->error('La longueur dois faire 7');
               }
           }*/

    }
}
