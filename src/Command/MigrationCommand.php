<?php

namespace App\Command;

use App\Entity\Entry;
use App\Factory\EntryFactory;
use App\Migration\RequestData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrationCommand extends Command
{
    protected static $defaultName = 'MigrationCommand';
    /**
     * @var RequestData
     */
    private $requestData;

    public function __construct(string $name = null, RequestData $requestData)
    {
        parent::__construct($name);
        $this->requestData = $requestData;
    }

    protected function configure()
    {
        $this
            ->setDescription('Migrations des données depuis un ancien Grr')
            ->addArgument('user', InputArgument::REQUIRED, "Le nom d'utilisateur d'un compte locale grr administrator")
            ->addArgument('password', InputArgument::REQUIRED, "Le mot de passe de l'utilisateur")
            ->addArgument('url', InputArgument::REQUIRED, "L'Url http de l'ancien Grr");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $user = $input->getArgument('user');
        $password = $input->getArgument('password');
        $url = $input->getArgument('url');

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $io->error(sprintf('L\'url n\'est pas valide: %s', $url));
        }

        $this->requestData->connect($url, $user, $password);

        $entries = $this->requestData->getEntries();
        if (isset($entries['error'])) {
            $io->error('Une erreur est survenue: '.$entries['error']);

            return;
        }

        foreach ($entries as $data) {
            var_dump($data);
            $entry = $entry = new Entry();
            $entry->setName($data['name']);
            $entry->setStartTime();
            $entry->setEndTime();
            $entry->setRoom();
            $entry->setBeneficiaire();
            $entry->setDescription();
            $entry->setCreatedBy();
            $entry->setStatutEntry();
            $entry->setType();
            $entry->setJours();
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
