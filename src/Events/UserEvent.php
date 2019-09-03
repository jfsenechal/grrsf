<?php

namespace App\Events;

use App\Entity\Entry;
use App\Entity\Security\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserEvent extends Event
{
    const USER_NEW_INITIALIZE = 'grr.user.new.initialize';
    const USER_NEW_SUCCESS = 'grr.user.new.success';
    const USER_NEW_COMPLETE = 'grr.user.new.complete';
    const USER_EDIT_SUCCESS = 'grr.user.edit.success';
    const USER_DELETE_SUCCESS = 'grr.user.delete.success';

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
