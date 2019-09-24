<?php

namespace App\Command;

use App\Factory\UserFactory;
use App\Manager\UserManager;
use App\Repository\Security\UserRepository;
use App\Security\PasswordHelper;
use App\Security\SecurityRole;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateuserCommand extends Command
{
    protected static $defaultName = 'grr:create-user';
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var string
     */
    private $name;
    /**
     * @var PasswordHelper
     */
    private $passwordHelper;

    public function __construct(
        string $name = null,
        UserManager $userManager,
        UserFactory $userFactory,
        UserRepository $userRepository,
        PasswordHelper $passwordHelper
    ) {
        parent::__construct($name);
        $this->userManager = $userManager;
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->passwordHelper = $passwordHelper;
    }

    protected function configure()
    {
        $this
            ->setDescription('Création d\'un utilisateur')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $role = SecurityRole::ROLE_GRR_ADMINISTRATOR;

        $email = $input->getArgument('email');
        $name = $input->getArgument('name');
        $password = $input->getArgument('password');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $io->error('Adresse email non valide');

            return 1;
        }

        if (strlen($name) < 1) {
            $io->error('Name minium 1');

            return 1;
        }

        if (!$password) {
            $question = new Question("Choisissez un mot de passe: \n");
            $question->setHidden(true);
            $question->setMaxAttempts(5);
            $question->setValidator(
                function ($password) {
                    if (strlen($password) < 4) {
                        throw new \RuntimeException(
                            'Le mot de passe doit faire minimum 4 caractères'
                        );
                    }

                    return $password;
                }
            );
            $password = $helper->ask($input, $output, $question);
        }

        if ($this->userRepository->findOneBy(['email' => $email])) {
            $io->error('Un utilisateur existe déjà avec cette adresse email');

            return 1;
        }

        $questionAdministrator = new ConfirmationQuestion("Administrateur de Grr ? [Y,n] \n", true);
        $administrator = $helper->ask($input, $output, $questionAdministrator);

        $user = $this->userFactory->createNew();
        $user->setEmail($email);
        $user->setUsername($email);
        $user->setName($name);
        $user->setPassword($this->passwordHelper->encodePassword($user, $password));

        if ($administrator) {
            $user->addRole($role);
        }

        $this->userManager->insert($user);

        $io->success("L'utilisateur a bien été créé");

        return null;
    }
}
