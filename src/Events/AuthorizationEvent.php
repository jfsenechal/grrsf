<?php

namespace App\Events;

use App\Entity\Security\UserAuthorization;
use Symfony\Contracts\EventDispatcher\Event;

class AuthorizationEvent extends Event
{
    const NEW_SUCCESS = 'grr.authorization.new.success';
    const EDIT_SUCCESS = 'grr.authorization.edit.success';
    const DELETE_SUCCESS = 'grr.authorization.delete.success';

    /**
     * @var UserAuthorization
     */
    private $userAuthorization;

    public function __construct(UserAuthorization $userAuthorization)
    {
        $this->userAuthorization = $userAuthorization;
    }

    /**
     * @return UserAuthorization
     */
    public function getUserAuthorization(): UserAuthorization
    {
        return $this->userAuthorization;
    }
}
