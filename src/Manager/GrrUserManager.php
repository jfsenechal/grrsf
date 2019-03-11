<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Repository\GrrUtilisateurRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class GrrUserManager
{
    /**
     * @var GrrUtilisateurRepository
     */
    private $grrUtilisateurRepository;

    public function __construct(GrrUtilisateurRepository $grrUtilisateurRepository)
    {
        $this->grrUtilisateurRepository = $grrUtilisateurRepository;
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

}