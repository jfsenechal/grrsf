<?php

namespace App\Handler;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Entity\Security\UserAuthorization;
use App\Manager\AuthorizationManager;
use App\Model\AuthorizationAreaModel;
use App\Repository\Security\AuthorizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class HandlerAuthorizationArea
{
    /**
     * @var AuthorizationManager
     */
    private $authorizationManager;
    /**
     * @var AuthorizationRepository
     */
    private $authorizationRepository;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        AuthorizationRepository $authorizationRepository,
        AuthorizationManager $authorizationManager,
        FlashBagInterface $flashBag
    ) {
        $this->authorizationManager = $authorizationManager;
        $this->authorizationRepository = $authorizationRepository;
        $this->flashBag = $flashBag;
    }

    public function handleNewUserManagerResource(FormInterface $form)
    {
        /**
         * @var AuthorizationAreaModel
         */
        $data = $form->getData();

        /**
         * @var User[]|ArrayCollection
         */
        $users = $data->getUsers();

        /**
         * @var Area
         */
        $area = $data->getArea();

        /**
         * @var Room[]|array
         */
        $rooms = $data->getRooms();

        /**
         * @var int $role
         */
        $role = $data->getRole();

        foreach ($users as $user) {
            $userAuthorization = new UserAuthorization();
            if ($role === 1) {
                $userAuthorization->setIsAreaAdministrator(true);
            }
            if ($role === 2) {
                $userAuthorization->setIsResourceAdministrator(true);
            }
            $userAuthorization->setUser($user);
            $userAuthorization->setArea($area);

            if ($this->existArea($user, $area, $rooms)) {
                $this->flashBag->add('danger', 'authorization.area.exist');
            } else {
                if (count($rooms) > 0) {
                    foreach ($rooms as $room) {
                        if ($this->existRoom($user, $area, $room)) {
                            $this->flashBag->add('danger', 'authorization.room.exist');
                        } else {
                            $userAuthorization->setRoom($room);
                            $this->authorizationManager->insert($userAuthorization);
                        }
                    }
                } else {
                    $this->authorizationManager->insert($userAuthorization);
                }
            }
        }
    }

    protected function existArea(User $user, Area $area, iterable $rooms): bool
    {
        $count = count($this->authorizationRepository->findBy(['user' => $user, 'area' => $area]));

        return $count > 0;
    }

    protected function existRoom(User $user, Area $area, Room $room): bool
    {
        $count = count($this->authorizationRepository->findBy(['user' => $user, 'area' => $area, 'room' => $room]));

        return $count > 0;
    }

}
