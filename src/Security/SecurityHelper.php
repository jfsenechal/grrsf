<?php

namespace App\Security;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Repository\Security\AuthorizationRepository;

class SecurityHelper
{
    /**
     * @var AuthorizationRepository
     */
    private $authorizationRepository;

    public function __construct(AuthorizationRepository $authorizationRepository)
    {
        $this->authorizationRepository = $authorizationRepository;
    }

    public function isAreaAdministrator(User $user, Area $area): bool
    {
        if ( $this->authorizationRepository->findOneBy(
            ['user' => $user, 'area' => $area, 'is_area_administrator' => true]
        )) {
            return true;
        }

        return false;
    }

    public function isAreaManager(User $user, Area $area): bool
    {
        if ($this->authorizationRepository->findOneBy(
            ['user' => $user, 'area' => $area, 'is_area_manager' => true]
        )) {
            return true;
        }

        return false;
    }

    public function isRoomAdministrator(User $user, Room $room): bool
    {
        $area = $room->getArea();

        if ($this->authorizationRepository->findOneBy(['user' => $user, 'area' => $area])) {
            return true;
        }

        return false;
    }

    public function isRoomManager(User $user, Room $room): bool
    {
        $area = $room->getArea();

        if ($this->authorizationRepository->findOneBy(['user' => $user, 'area' => $area])) {
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

        if ($this->authorizationRepository->findOneBy(['user' => $user, 'room' => $room])) {
            return true;
        }

        return false;
    }

    public function isAreaRestricted(Area $area): bool
    {
        return false;
    }

    /**
     * @param Area $area
     * @param User $user
     *
     * @return bool
     *
     * @todo
     */
    public function canSeeArea(Area $area, User $user): bool
    {
        return true;
    }

    /**
     * @param Room $room
     * @param User|null $user null => user anonyme
     *
     * @return bool
     *
     * @todo
     */
    public function canSeeRoom(Room $room, User $user = null): bool
    {
        return true;
        $t = [
            0 => "
        N'importe qui allant sur le site même s'il n'est pas connecté",
            1 => 'il faut obligatoirement être connecté, même en simple visiteur.',
            2 => 'Il faut obligatoirement être connecté et avoir le statut utilisateur',
            3 => "Il faut obligatoirement être connecté et être au moins gestionnaire d'une ressource",
            4 => 'Il faut obligatoirement se connecter et être au moins administrateur du domaine',
            6 => 'Il faut obligatoirement être connecté et être administrateur général',
        ];
    }

    /**
     * @return bool
     *
     * @todo
     */
    public function canSeeAreaRestricted(Area $area, User $user): bool
    {
        return true;
    }
}
