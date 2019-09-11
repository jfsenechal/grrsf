<?php

namespace App\Security;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Repository\Security\AuthorizationRepository;
use App\Setting\SettingsRoom;

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

    /**
     * Tous les droits sur l'Area et ses ressources modifier ses paramètres, la supprimer
     * Peux encoder des entry dans toutes les ressources de l'Area
     * @param User $user
     * @param Area $area
     * @return bool
     */
    public function isAreaAdministrator(User $user, Area $area): bool
    {
        if ($this->authorizationRepository->findOneBy(
            ['user' => $user, 'area' => $area, 'is_area_administrator' => true]
        )) {
            return true;
        }

        return false;
    }

    /**
     * Peux gérer les ressources mais pas modifier l'Area
     * Peux encoder des entry dans toutes les ressources de l'Area
     *
     * @param User $user
     * @param Area $area
     * @return bool
     */
    public function isAreaManager(User $user, Area $area): bool
    {
        if ($this->isAreaAdministrator($user, $area)) {
            return true;
        }

        if ($this->authorizationRepository->findOneBy(
            ['user' => $user, 'area' => $area, 'is_area_administrator' => false]
        )) {
            return true;
        }

        return false;
    }

    /**
     * Peux gérer la room (modifier les paramètres) et pas de contraintes pour encoder les entry
     * @param User $user
     * @param Room $room
     * @return bool
     */
    public function isRoomAdministrator(User $user, Room $room): bool
    {
        if ($this->isAreaAdministrator($user, $room->getArea())) {
            return true;
        }
        if ($this->authorizationRepository->findOneBy(
            ['user' => $user, 'room' => $room, 'is_resource_administrator' => true]
        )) {
            return true;
        }

        return false;
    }

    /**
     * Peux gérer toutes les entrées sans contraintes
     * @param User $user
     * @param Room $room
     * @return bool
     */
    public function isRoomManager(User $user, Room $room): bool
    {
        if ($this->isRoomAdministrator($user, $room)) {
            return true;
        }

        if ($this->authorizationRepository->findOneBy(
            ['user' => $user, 'room' => $room, 'is_resource_administrator' => false]
        )) {
            return true;
        }

        return false;
    }

    public function isGrrAdministrator(User $user)
    {
        return $user->hasRole(SecurityRole::ROLE_GRR_ADMINISTRATOR);
    }

    /**
     * @param Room $room
     * @param User|null $user
     * @return bool
     */
    public function checkAuthorizationRoomToAddEntry(Room $room, User $user = null): bool
    {
        $who = $room->getWhoCanAdd();
        /**
         * Si pas de user et pas de règle definie
         */
        if (!$who && !$user) {
            return false;
        }

        //tout le monde peut encoder une réservation meme si pas connecte
        if ($who === SettingsRoom::EVERY_BODY) {
            return true;
        }

        //il faut être connecté
        if ($who === SettingsRoom::EVERY_CONNECTED) {
            if (!$user) {
                return false;
            }

            return $user->hasRole(SecurityRole::ROLE_GRR);
        }

        /**
         * A partir d'ici il faut être connecté
         */
        if (!$user) {
            return false;
        }

        /**
         * il faut être connecté et avoir le role @see SecurityRole::ROLE_GRR_ACTIVE_USER
         */
        if ($who === SettingsRoom::EVERY_USER_ACTIVE) {
            return $user->hasRole(SecurityRole::ROLE_GRR_ACTIVE_USER);
        }

        /**
         * Il faut être administrateur de la room
         */
        if ($who === SettingsRoom::EVERY_ROOM_ADMINISTRATOR) {
            return $this->isRoomAdministrator($user, $room);
        }

        /**
         * Il faut être manager de la room
         */
        if ($who === SettingsRoom::EVERY_ROOM_MANAGER) {
            return $this->isRoomManager($user, $room);
        }

        /**
         * Il faut être administrateur de l'area
         */
        if ($who === SettingsRoom::EVERY_AREA_ADMINISTRATOR) {
            $area = $room->getArea();

            return $this->isAreaAdministrator($user, $area);
        }

        /**
         * Il faut être manager de l'area
         */
        if ($who === SettingsRoom::EVERY_AREA_MANAGER) {
            $area = $room->getArea();

            return $this->isAreaManager($user, $area);
        }

        /**
         * Il faut être administrateur de Grr
         */
        if ($who === SettingsRoom::EVERY_GRR_ADMINISTRATOR) {
            return $this->isGrrAdministrator($user);
        }


        return false;
    }

    /**
     * @param User|null $user
     * @param Room $room
     * @return bool
     */
    public function canAddEntry(Room $room, ?User $user = null)
    {
        if (!$user) {
            return $this->checkAuthorizationRoomToAddEntry($room, $user);
        }

        $area = $room->getArea();
        if ($this->isAreaAdministrator($user, $area)) {
            return true;
        }
        if ($this->isAreaManager($user, $area)) {
            return true;
        }
        if ($this->isRoomAdministrator($user, $room)) {
            return true;
        }
        if ($this->isRoomManager($user, $room)) {
            return true;
        }

        return false;
    }

    public function isAreaRestricted(Area $area): bool
    {
        return $area->getIsRestricted();
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
     * @return bool
     *
     * @todo
     */
    public function canSeeAreaRestricted(Area $area, User $user): bool
    {
        return true;
    }
}
