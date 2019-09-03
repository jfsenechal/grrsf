<?php

namespace App\Events;

use App\Entity\Security\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserEvent extends Event
{
    const NEW_SUCCESS = 'grr.user.new.success';
    const EDIT_SUCCESS = 'grr.user.edit.success';
    const DELETE_SUCCESS = 'grr.user.delete.success';

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
