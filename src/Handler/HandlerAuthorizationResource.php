<?php

namespace App\Handler;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Entity\Security\UserAuthorization;
use App\Manager\AuthorizationManager;
use App\Model\AuthorizationResourceModel;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;

class HandlerAuthorizationResource
{
    /**
     * @var AuthorizationManager
     */
    private $userManagerResourceManager;

    public function __construct(
        AuthorizationManager $userManagerResourceManager
    ) {
        $this->userManagerResourceManager = $userManagerResourceManager;
    }

    public function handleNewUserManagerResource(FormInterface $form)
    {
        /**
         * @var AuthorizationResourceModel
         */
        $data = $form->getData();

        /**
         * @var User[]|ArrayCollection
         */
        $users = $data->getUsers();
        /**
         * @var Room[]|ArrayCollection
         */
        $rooms = $data->getRooms();
        /**
         * @var Area
         */
        $area = $data->getArea();
        /**
         * 1 => administrator
         * 2 => manager.
         *
         * @var int
         */
        $areaLevel = $data->getAreaLevel();

        foreach ($users as $user) {
            $userManagerResource = new UserAuthorization();
            $userManagerResource->setUser($user);
            if (1 == $areaLevel) {
                $userManagerResource->setArea($area);
                $userManagerResource->setIsAreaAdministrator(true);
                $this->userManagerResourceManager->insert($userManagerResource);
                continue;
            }
            if (2 == $areaLevel) {
                $userManagerResource->setArea($area);
                $userManagerResource->setIsRoomadministrator(true);
                $this->userManagerResourceManager->insert($userManagerResource);
                continue;
            }

            foreach ($rooms as $room) {
                $userManagerResource = new UserAuthorization();
                $userManagerResource->setUser($user);
                $userManagerResource->setRoom($room);
                $this->userManagerResourceManager->insert($userManagerResource);
            }
        }
    }

    // $this->userManagerResourceManager->insert($userManagerResource);
}
