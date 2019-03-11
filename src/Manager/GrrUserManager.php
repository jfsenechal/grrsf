<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class GrrUserManager
{
    /**
     * @var UserRepository
     */
    private $grrUtilisateurRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(
        UserRepository $grrUtilisateurRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->grrUtilisateurRepository = $grrUtilisateurRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function persist(UserInterface $user)
    {
        $this->grrUtilisateurRepository->persist($user);
    }

    public function remove(UserInterface $user)
    {
        $this->grrUtilisateurRepository->remove($user);
    }

    public function flush()
    {
        $this->grrUtilisateurRepository->flush();
    }

    public function insert(UserInterface $user)
    {
        $this->grrUtilisateurRepository->insert($user);
    }

    public function encodePassword(User $user, string $clearPassword)
    {
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $clearPassword));
    }


}