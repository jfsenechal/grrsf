<?php

namespace App\Command;

use App\Entity\Security\User;
use App\Manager\UserManager;
use App\Repository\Security\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateuserCommand extends Command
{
    protected static $defaultName = 'grr:createuser';
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        ?string $name = null,
        UserManager $userManager,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        parent::__construct($name);
        $this->userManager = $userManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->userRepository = $userRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('email', InputArgument::REQUIRED, 'Adresse email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Mot de passe');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $name = $input->getArgument('name');
        $password = $input->getArgument('password');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $io->error('Adresse email non valide');

            return;
        }

        if (strlen($name) < 4) {
            $io->error('Name minium 4');

            return;
        }

        if (!$password) {
            $helper = $this->getHelper('question');
            $question = new Question('Choisissez un mot de passe');

            $password = $helper->ask($input, $output, $question);
        }

        if (strlen($password) < 4) {
            $io->error('Password length minium 4');

            return;
        }

        if ($this->userRepository->findOneBy(['email' => $email])) {
            $io->error('Un utilisateur existe déjà avec cette adresse email');

            return;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($this->userManager->encodePassword($user, $password));

        $this->userManager->insert($user);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
