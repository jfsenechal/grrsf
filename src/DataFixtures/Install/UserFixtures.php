<?php

namespace App\DataFixtures\Install;

use App\Factory\UserFactory;
use App\Security\SecurityData;
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

    public function __construct(UserFactory $userFactory, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userFactory = $userFactory;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $role = SecurityData::getRoleGrrAdministrator();

        $user = $this->userFactory->createNew();
        $user->setName('Jf');
        $user->setEmail('jf@marche.be');
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'homer'));
        $user->addRole($role);

        $manager->persist($user);
        $manager->flush();
    }
}
