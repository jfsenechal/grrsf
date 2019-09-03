<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

use App\Entity\Security\UserAuthorization;
use App\Repository\Security\AuthorizationRepository;

class AuthorizationManager
{
    /**
     * @var AuthorizationRepository
     */
    private $authorizationRepository;

    public function __construct(AuthorizationRepository $userManagerResourceRepository)
    {
        $this->authorizationRepository = $userManagerResourceRepository;
    }

    public function persist(UserAuthorization $userManagerResource)
    {
        $this->authorizationRepository->persist($userManagerResource);
    }

    public function remove(UserAuthorization $userManagerResource)
    {
        $this->authorizationRepository->remove($userManagerResource);
    }

    public function flush()
    {
        $this->authorizationRepository->flush();
    }

    public function insert(UserAuthorization $userManagerResource)
    {
        $this->authorizationRepository->insert($userManagerResource);
    }
}
