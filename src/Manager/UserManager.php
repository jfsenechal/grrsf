<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

use App\Repository\Security\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{
    /**
     * @var UserRepository
     */
    private $UtilisateurRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(
        UserRepository $UtilisateurRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->UtilisateurRepository = $UtilisateurRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function persist(UserInterface $user)
    {
        $this->UtilisateurRepository->persist($user);
    }

    public function remove(UserInterface $user)
    {
        $this->UtilisateurRepository->remove($user);
    }

    public function flush()
    {
        $this->UtilisateurRepository->flush();
    }

    public function insert(UserInterface $user)
    {
        $this->UtilisateurRepository->insert($user);
    }
}
