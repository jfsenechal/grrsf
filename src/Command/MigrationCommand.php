<?php

namespace App\Command;

use App\Entity\Area;
use App\Migration\MigrationFactory;
use App\Migration\MigrationUtil;
use App\Migration\RequestData;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidDateException;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrationCommand extends Command
{
    protected static $defaultName = 'grr:migration';
    /**
     * @var RequestData
     */
    private $requestData;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SymfonyStyle
     */
    private $io;
    /**
     * @var array|null
     */
    private $rooms;
    /**
     * @var MigrationUtil
     */
    private $migrationUtil;
    /**
     * @var array|null
     */
    private $areas;
    /**
     * @var MigrationFactory
     */
    private $migrationFactory;

    public function __construct(
        string $name = null,
        RequestData $requestData,
        EntityManagerInterface $entityManager,
        MigrationUtil $migrationUtil,
        MigrationFactory $migrationFactory
    ) {
        parent::__construct($name);
        $this->requestData = $requestData;
        $this->entityManager = $entityManager;
        $this->migrationUtil = $migrationUtil;
        $this->migrationFactory = $migrationFactory;
    }

    protected function configure()
    {
        $this
            ->setDescription('Migrations des données depuis un ancien Grr')
            ->addArgument('url', InputArgument::REQUIRED, "L'Url http de l'ancien Grr")
            ->addArgument('user', InputArgument::REQUIRED, "Le nom d'utilisateur d'un compte locale grr administrator")
            ->addArgument('password', InputArgument::OPTIONAL, "Le mot de passe de l'utilisateur");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $user = $input->getArgument('user');
        $password = $input->getArgument('password');
        $url = $input->getArgument('url');

        if ($parts = parse_url($url)) {
            if (!isset($parts['scheme'])) {
                $this->io->error(sprintf('L\'url n\'est pas valide: %s', $url));

                return;
            }
        }

        if (!$password) {
            $question = new Question("Le mot de passe de $user: \n");
            $question->setHidden(true);
            $question->setMaxAttempts(5);
            $question->setValidator(
                function ($password) {
                    if (strlen($password) < 2) {
                        throw new \RuntimeException(
                            'Le mot de passe ne peut être vide'
                        );
                    }

                    return $password;
                }
            );
            $password = $helper->ask($input, $output, $question);
        }

        $date = null;
        $questionDate = new Question(
            "A partir de quelle date voulez vous importer les entrées, par exemple: 2017-11-25. Laissez vide pour importer tout: \n"
        );
        $questionDate->setValidator(
            function ($date) {
                if ($date == null) {
                    return $date;
                }

                try {
                    var_dump($date);

                    return Carbon::createFromFormat('Y-m-d', $date);

                } catch (InvalidDateException $exception) {
                    throw new \RuntimeException(
                        'La date n\'a pas un format valable: '.$exception->getMessage()
                    );
                }

                return $date;
            }
        );
        $date = $helper->ask($input, $output, $questionDate);

        if ($date) {
            $this->io->success('Date choisie : '.$date->format('Y-m-d'));
        }

        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $this->requestData->connect($url, $user, $password);

        $this->handleArea();
        $this->handleEntryType();
        //  $this->handleUser();
        $this->handleEntry($date);

        $this->entityManager->flush();

        $this->io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }

    protected function handleArea()
    {
        $this->areas = $this->decompress($this->requestData->getAreas());
        $this->rooms = $this->decompress($this->requestData->getRooms());

        foreach ($this->areas as $data) {
            $area = $this->migrationFactory->createArea($data);
            $this->entityManager->persist($area);
            $this->handleRoom($area, $data['id']);
        }
    }

    protected function handleRoom(Area $area, int $areaId)
    {
        foreach ($this->rooms as $data) {
            if ($data['area_id'] == $areaId) {
                $room = $this->migrationFactory->createRoom($area, $data);
                $this->entityManager->persist($room);
            }
        }
    }

    protected function handleEntryType()
    {
        $types = $this->decompress($this->requestData->getTypesEntry());

        foreach ($types as $data) {
            $type = $this->migrationFactory->createTypeEntry($data);
            $this->entityManager->persist($type);
        }
    }

    protected function handleUser()
    {
        $users = $this->decompress($this->requestData->getUsers());

        foreach ($users as $data) {
            if ($error = $this->migrationUtil->checkUser($data)) {
                $this->io->note('Utilisateur non ajouté: '.$error);
            } else {
                $user = $this->migrationFactory->createUser($data);
                $user->setPassword($this->migrationUtil->transformPassword($user, $data['password']));
                $user->setAreaDefault($this->migrationUtil->transformDefaultArea($this->areas, $data['default_area']));
                $user->setRoomDefault($this->migrationUtil->transformDefaultRoom($this->rooms, $data['default_room']));
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
        }
    }

    protected function handleEntry(?string $date)
    {
        $entries = $this->decompress($this->requestData->getEntries($date));

        foreach ($entries as $data) {
            $entry = $this->migrationFactory->createEntry($data);
            $this->entityManager->persist($entry);
        }
    }

    private function decompress(string $content): ?array
    {
        $data = json_decode($content, true);

        if (!is_array($data)) {
            $this->io->error('La réponse doit être un json: '.$content);

            return null;
        }

        if (isset($data['error'])) {
            $this->io->error('Une erreur est survenue: '.$data['error']);

            return null;
        }

        return $data;
    }


}
