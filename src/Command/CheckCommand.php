<?php

namespace App\Command;

use App\Repository\Security\AuthorizationRepository;
use App\Repository\Security\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckCommand extends Command
{
    protected static $defaultName = 'grr:check';
    /**
     * @var AuthorizationRepository
     */
    private $authorizationRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(string $name = null, UserRepository $userRepository,AuthorizationRepository $authorizationRepository)
    {
        parent::__construct($name);
        $this->authorizationRepository = $authorizationRepository;
        $this->userRepository = $userRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('what', InputArgument::OPTIONAL, 'Que voulez vous checker')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $what = $input->getArgument('what');



        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
