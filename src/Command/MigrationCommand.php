<?php

namespace App\Command;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use App\Migration\AreaMigration;
use App\Migration\MigrationUtil;
use App\Migration\RequestData;
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

    public function __construct(string $name = null, RequestData $requestData, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->requestData = $requestData;
        $this->entityManager = $entityManager;
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

        $this->requestData->connect($url, $user, $password);

        $this->handleArea();

        $this->entityManager->flush();

        $this->io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }

    protected function handleArea()
    {
        $entries = $this->decompress($this->requestData->getAreas());

        foreach ($entries as $data) {
            $area = MigrationUtil::createArea($data);
            $this->entityManager->persist($area);
            $this->handleRoom($area);
        }
    }

    protected function handleRoom(Area $area)
    {
        $rooms = $this->decompress($this->requestData->getRooms());

        foreach ($rooms as $data) {
            $area = MigrationUtil::createRoom($area,$data);
            $this->entityManager->persist($area);
        }
    }

    private function decompress(string $content) : ?array
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
