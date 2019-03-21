<?php

namespace App\Command;

use App\Repository\AreaRepository;
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
    private $grrAreaRepository;
    /**
     * @var SymfonyStyle
     */
    private $io;


    public function __construct(
        ?string $name = null,
        AreaRepository $grrAreaRepository
    ) {
        parent::__construct($name);
        $this->grrAreaRepository = $grrAreaRepository;
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

    /**
     *
     */
    protected function areaUpgrade()
    {
        $areas = $this->grrAreaRepository->findAll();

        foreach ($areas as $area) {
            $displayDays = $area->getDisplayDays();
            if (strlen($displayDays) == 7) {
                var_dump(str_split($displayDays));
            } else {
                $this->io->error('La longueur dois faire 7');
            }

        }
    //    $this->grrEntryManager->flush();
    }

}
