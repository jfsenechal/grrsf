<?php

namespace App\DataFixtures\Install;

use App\Factory\UserFactory;
use App\Repository\Security\UserRepository;
use App\Security\SecurityRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserRepository $userRepository,
        UserFactory $userFactory,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->userFactory = $userFactory;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        $email = 'jf@marche.be';
        $password = 'homer';

        if ($this->userRepository->findOneBy(['email' => $email])) {
            return;
        }

        $roleGrrAdministrator = SecurityRole::getRoleGrrAdministrator();

        $user = $this->userFactory->createNew();
        $user->setName('Administrator');
        $user->setFirstName('Grr');
        $user->setEmail($email);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));
        $user->addRole($roleGrrAdministrator);

        $manager->persist($user);
        $manager->flush();
    }
}
