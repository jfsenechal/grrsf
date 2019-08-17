<?php


namespace App\Security;


use App\Entity\Area;
use App\Entity\Security\User;
use App\Repository\Security\UserManagerResourceRepository;

class SecurityHelper
{
    /**
     * @var UserManagerResourceRepository
     */
    private $userManagerResourceRepository;

    public function __construct(UserManagerResourceRepository $userManagerResourceRepository)
    {
        $this->userManagerResourceRepository = $userManagerResourceRepository;
    }

    public function isAreaAdministrator(User $user, Area $area): bool
    {
        if (!$this->userManagerResourceRepository->findOneBy(
            ['user' => $user, 'area' => $area, 'is_area_administrator' => true]
        )) {
            return false;
        }

        return true;
    }

    public function isAreaManager(User $user, Area $area): bool
    {
        if (!$this->userManagerResourceRepository->findOneBy(
            ['user' => $user, 'area' => $area, 'is_area_manager' => true]
        )) {
            return false;
        }

        return true;
    }

}