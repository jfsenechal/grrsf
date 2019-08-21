<?php

namespace App\Command;

use App\Entity\Security\User;
use App\Factory\UserFactory;
use App\Manager\UserManager;
use App\Repository\Security\UserRepository;
use App\Security\SecurityData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
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
    /**
     * @var UserFactory
     */
    private $userFactory;

    public function __construct(
        ?string $name = null,
        UserManager $userManager,
        UserFactory $userFactory,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        parent::__construct($name);
        $this->userManager = $userManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
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
        $helper = $this->getHelper('question');
        $role = SecurityData::getRoleGrrAdministrator();

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

            return;
        }

        $questionAdministrator = new ConfirmationQuestion("Administrateur de Grr ? [Y,n] \n", true);
        $administrator = $helper->ask($input, $output, $questionAdministrator);

        $user = $this->userFactory->createNew();
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($this->userManager->encodePassword($user, $password));

        if ($administrator) {
            $user->addRole($role);
        }

        $this->userManager->insert($user);

        $io->success("L'utilisateur a bien été créé");
    }
}
