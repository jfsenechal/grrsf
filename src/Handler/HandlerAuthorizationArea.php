<?php

namespace App\Handler;

use App\Entity\Area;
use App\Entity\Security\User;
use App\Entity\Security\UserAuthorization;
use App\Manager\AuthorizationManager;
use App\Model\AuthorizationAreaModel;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;

class HandlerAuthorizationArea
{
    /**
     * @var AuthorizationManager
     */
    private $authorizationManager;

    public function __construct(
        AuthorizationManager $authorizationManager
    ) {
        $this->authorizationManager = $authorizationManager;
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
            $this->authorizationManager->insert($userAuthorization);
        }
    }

}
