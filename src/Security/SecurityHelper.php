<?php


namespace App\Security;

use App\Entity\Area;
use App\Entity\Room;
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
        if ($this->userManagerResourceRepository->findOneBy(
            ['user' => $user, 'area' => $area, 'is_area_administrator' => true]
        )) {
            return true;
        }

        return false;
    }

    public function isAreaManager(User $user, Area $area): bool
    {
        if ($this->userManagerResourceRepository->findOneBy(
            ['user' => $user, 'area' => $area, 'is_area_manager' => true]
        )) {
            return true;
        }

        return false;
    }

    public function isRoomAdministrator(User $user, Room $room): bool
    {
        $area = $room->getArea();

        if ($this->userManagerResourceRepository->findOneBy(['user' => $user, 'area' => $area])) {
            return true;
        }

        return false;
    }

    public function isRoomManager(User $user, Room $room): bool
    {
        $area = $room->getArea();

        if ($this->userManagerResourceRepository->findOneBy(['user' => $user, 'area' => $area])) {
            return true;
        }

        return false;
    }

    public function canAddEntry(User $user, Room $room)
    {
        $area = $room->getArea();
        if ($this->isRoomManager($user, $room)) {
            return true;
        }
        if ($this->isRoomAdministrator($user, $room)) {
            return true;
        }
        if ($this->isAreaAdministrator($user, $area)) {
            return true;
        }
        if ($this->isAreaManager($user, $area)) {
            return true;
        }

        if ($this->userManagerResourceRepository->findOneBy(['user' => $user, 'room' => $room])) {
            return true;
        }

        return false;
    }

}