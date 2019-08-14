<?php

namespace App\Command;

use App\Entity\Security\User;
use App\Manager\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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

    public function __construct(
        ?string $name = null,
        UserManager $userManager,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        parent::__construct($name);
        $this->userManager = $userManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('email', InputArgument::REQUIRED, 'Adresse email')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe');
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

        if (strlen($password) < 4) {
            $io->error('Password minium 4');

            return;
        }

        if (strlen($name) < 4) {
            $io->error('Name minium 4');

            return;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setName($name);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));

        $this->userManager->insert($user);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
