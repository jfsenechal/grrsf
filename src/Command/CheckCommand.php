<?php

namespace App\Command;

use App\Checker\MigrationChecker;
use App\Repository\RoomRepository;
use App\Repository\Security\AuthorizationRepository;
use App\Repository\Security\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckCommand extends Command
{
    protected static $defaultName = 'grr:check';
    /**
     * @var MigrationChecker
     */
    private $migrationChecker;

    public function __construct(
        string $name = null,
        MigrationChecker $migrationChecker
    ) {
        parent::__construct($name);
        $this->migrationChecker = $migrationChecker;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('what', InputArgument::OPTIONAL, 'Que voulez vous checker')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $what = $input->getArgument('what');

        $authorizations = $this->migrationChecker->checkAreaAndRoomAdministrator();
        if (count($authorizations) > 0) {
            $io->warning('Ces authorizations sont en double');
            foreach ($authorizations as $authorization) {
                $user = $authorization['user'] != null ? $authorization['user']->getEmail() : '';
                $area = $authorization['area'] != null ? $authorization['area']->getName() : '';
                $room = $authorization['room'] != null ? $authorization['room']->getName() : '';

                $io->note($user.' ==> '.$area.' ==> '.$room." \n ");
            }
            $questionDelete = new ConfirmationQuestion("Les supprimer ? [y,N] \n", false);
            $delete = $helper->ask($input, $output, $questionDelete);
            if ($delete) {
                $this->migrationChecker->deleteDoublone();
                $io->success("Doublons supprimés");
            }
        }
    }


}
