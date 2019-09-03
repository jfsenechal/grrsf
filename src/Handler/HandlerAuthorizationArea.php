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

        foreach ($users as $user) {
            $userManagerResource = new UserAuthorization();
            $userManagerResource->setUser($user);
            $userManagerResource->setArea($area);
            $userManagerResource->setIsAreaAdministrator(true);
            $this->authorizationManager->insert($userManagerResource);
        }
    }

}
