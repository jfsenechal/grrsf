<?php

namespace App\Command;

use App\Checker\MigrationChecker;
use App\Migration\MigrationUtil;
use App\Repository\EntryRepository;
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
    /**
     * @var SymfonyStyle
     */
    private $io;
    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var MigrationUtil
     */
    private $migrationUtil;

    public function __construct(
        string $name = null,
        MigrationChecker $migrationChecker,
        EntryRepository $entryRepository,
        MigrationUtil $migrationUtil
    ) {
        parent::__construct($name);
        $this->migrationChecker = $migrationChecker;
        $this->entryRepository = $entryRepository;
        $this->migrationUtil = $migrationUtil;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('what', InputArgument::OPTIONAL, 'Que voulez vous checker')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->io = new SymfonyStyle($input, $output);
        $this->output = $output;
        $helper = $this->getHelper('question');
        $what = $input->getArgument('what');

        $authorizations = $this->migrationChecker->checkAreaAndRoomAdministrator();
        if (count($authorizations) > 0) {
            $io->warning('Ces authorizations sont en double');
            foreach ($authorizations as $authorization) {
                $user = null != $authorization['user'] ? $authorization['user']->getEmail() : '';
                $area = null != $authorization['area'] ? $authorization['area']->getName() : '';
                $room = null != $authorization['room'] ? $authorization['room']->getName() : '';

                $io->note($user.' ==> '.$area.' ==> '.$room." \n ");
            }
            $questionDelete = new ConfirmationQuestion("Les supprimer ? [y,N] \n", false);
            $delete = $helper->ask($input, $output, $questionDelete);
            if ($delete) {
                $this->migrationChecker->deleteDoublon();
                $io->success('Doublons supprimés');
            }
        }

        $this->io->section('Check entry: '.MigrationUtil::FOLDER_CACHE);
        $this->io->newLine();

        $fileHandler = file_get_contents(MigrationUtil::FOLDER_CACHE.'entry.json');
        $entries = json_decode($fileHandler, true);

        $fileHandler = file_get_contents(MigrationUtil::FOLDER_CACHE.'resolveroom.json');
        $rooms = unserialize($fileHandler, false);

        $fileHandler = file_get_contents(MigrationUtil::FOLDER_CACHE.'resolverepeat.json');
        $periodicities = unserialize($fileHandler, false);

        foreach ($entries as $data) {
            $name = $data['name'];
            $startTime = $this->migrationUtil->converToDateTime($data['start_time']);
            $endTime = $this->migrationUtil->converToDateTime($data['end_time']);
            $room = $this->migrationUtil->transformToRoom($rooms, $data['room_id']);
            $repeatId = (int) $data['repeat_id'];

            $args = ['start_time' => $startTime, 'end_time' => $endTime, 'name' => $name, 'room' => $room];

            if ($repeatId > 0) {
                $periodicity = $periodicities[$repeatId];
                $args['periodicity'] = $periodicity;
            }

            $entry = $this->entryRepository->findOneBy(
                $args
            );

            if (!$entry) {
                $io->error($data['name'].' '.$startTime->format('d-m-Y'));
            } else {
                // $io->success($entry->getName().' ==> '.$name);
            }
        }

        return 0;
    }
}
