<?php

namespace App\Handler;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Entity\Security\UserManagerResource;
use App\Manager\UserManagerResourceManager;
use App\Model\UserManagerResourceModel;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;

class HandlerUserManagerResource
{
    /**
     * @var UserManagerResourceManager
     */
    private $userManagerResourceManager;

    public function __construct(
        UserManagerResourceManager $userManagerResourceManager
    ) {
        $this->userManagerResourceManager = $userManagerResourceManager;
    }

    public function handleNewUserManagerResource(FormInterface $form)
    {
        /**
         * @var UserManagerResourceModel
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
            $userManagerResource = new UserManagerResource();
            $userManagerResource->setUser($user);
            if (1 == $areaLevel) {
                $userManagerResource->setArea($area);
                $userManagerResource->setIsAreaAdministrator(true);
                $this->userManagerResourceManager->insert($userManagerResource);
                continue;
            }
            if (2 == $areaLevel) {
                $userManagerResource->setArea($area);
                $userManagerResource->setIsAreaManager(true);
                $this->userManagerResourceManager->insert($userManagerResource);
                continue;
            }

            foreach ($rooms as $room) {
                $userManagerResource = new UserManagerResource();
                $userManagerResource->setUser($user);
                $userManagerResource->setRoom($room);
                $this->userManagerResourceManager->insert($userManagerResource);
            }
        }
    }

    // $this->userManagerResourceManager->insert($userManagerResource);
}
