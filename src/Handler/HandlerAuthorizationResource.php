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
         * @var AuthorizationResourceModel $data
         */
        $data = $form->getData();
        /**
         * @var User[]|ArrayCollection $users
         */
        $users = $data->getUsers();
        /**
         * @var Area
         */
        $area = $data->getArea();
        /**
         * @var Room[]|ArrayCollection $rooms
         */
        $rooms = $data->getRooms();

        foreach ($users as $user) {
            $userAuthorization = new UserAuthorization();
            $userAuthorization->setUser($user);
            foreach ($rooms as $room) {
                $userAuthorization = new UserAuthorization();
                $userAuthorization->setUser($user);
                $userAuthorization->setRoom($room);
                $this->userManagerResourceManager->insert($userAuthorization);
            }
        }
    }
}
