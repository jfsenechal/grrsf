<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

use App\Entity\Security\UserManagerResource;
use App\Repository\Security\UserManagerResourceRepository;

class UserManagerResourceManager
{
    /**
     * @var UserManagerResourceRepository
     */
    private $userManagerResourceRepository;

    public function __construct(UserManagerResourceRepository $userManagerResourceRepository)
    {
        $this->userManagerResourceRepository = $userManagerResourceRepository;
    }

    public function persist(UserManagerResource $userManagerResource)
    {
        $this->userManagerResourceRepository->persist($userManagerResource);
    }

    public function remove(UserManagerResource $userManagerResource)
    {
        $this->userManagerResourceRepository->remove($userManagerResource);
    }

    public function flush()
    {
        $this->userManagerResourceRepository->flush();
    }

    public function insert(UserManagerResource $userManagerResource)
    {
        $this->userManagerResourceRepository->insert($userManagerResource);
    }
}
