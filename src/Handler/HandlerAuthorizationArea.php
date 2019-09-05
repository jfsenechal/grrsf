<?php

namespace App\Handler;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Entity\Security\UserAuthorization;
use App\Manager\AuthorizationManager;
use App\Model\AuthorizationModel;
use App\Repository\Security\AuthorizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var bool
     */
    private $error;

    public function __construct(
        AuthorizationRepository $authorizationRepository,
        AuthorizationManager $authorizationManager,
        FlashBagInterface $flashBag,
        TranslatorInterface $translator
    ) {
        $this->authorizationManager = $authorizationManager;
        $this->authorizationRepository = $authorizationRepository;
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    public function handleNewUserManagerResource(FormInterface $form)
    {
        /**
         * @var AuthorizationModel
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

        $this->error = false;
        dump($users);
        foreach ($users as $user) {
            dump($rooms);
            $userAuthorization = new UserAuthorization();
            $userAuthorization->setUser($user);

            if ($role === 1) {
                $userAuthorization->setIsAreaAdministrator(true);
            }
            if ($role === 2) {
                $userAuthorization->setIsResourceAdministrator(true);
            }

            if (count($rooms) > 0) {
                $this->executeForRooms($userAuthorization, $area, $rooms, $user);
            } else {
                $this->executeForArea($userAuthorization, $area, $user);
            }
        }

        if (!$this->error) {
            $this->flashBag->add('success', 'authorization.flash.model.new');
        }
    }

    protected function executeForRooms(
        UserAuthorization $userAuthorization,
        Area $area,
        iterable $rooms,
        $user
    ) {
        if ($this->existArea($user, $area)) {
            $this->error = true;
            $this->flashBag->add(
                'danger',
                $this->translator->trans(
                    'authorization.area.exist',
                    [
                        'user' => $user,
                        'area' => $area,
                    ]
                )
            );

            return;
        }
        foreach ($rooms as $room) {
            dump($area);
            $copy = clone($userAuthorization);
            if ($this->existRoom($user, $room)) {
                $this->error = true;
                $this->flashBag->add(
                    'danger',
                    $this->translator->trans('authorization.room.exist', ['user' => $user, 'room' => $room])
                );
            } else {
                $copy->setRoom($room);
                $this->authorizationManager->insert($copy);
            }
        }
    }

    protected function executeForArea(UserAuthorization $userAuthorization, Area $area, User $user)
    {
        if ($this->existArea($user, $area)) {
            $this->error = true;
            $this->flashBag->add(
                'danger',
                $this->translator->trans(
                    'authorization.area.exist',
                    [
                        'user' => $user,
                        'area' => $area,
                    ]
                )
            );
        } else {
            $userAuthorization->setArea($area);
            $this->authorizationManager->insert($userAuthorization);
        }
    }

    protected function existArea(User $user, Area $area): bool
    {
        $count = count($this->authorizationRepository->findBy(['user' => $user, 'area' => $area]));

        return $count > 0;
    }

    protected function existRoom(User $user, Room $room): bool
    {
        $count = count($this->authorizationRepository->findBy(['user' => $user, 'room' => $room]));

        return $count > 0;
    }

}
