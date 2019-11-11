<?php

namespace App\Events;

use App\Entity\Security\Authorization;
use Symfony\Contracts\EventDispatcher\Event;

class AuthorizationEvent extends Event
{
    const NEW_SUCCESS = 'grr.authorization.new.success';
    const EDIT_SUCCESS = 'grr.authorization.edit.success';
    const DELETE_SUCCESS = 'grr.authorization.delete.success';

    /**
     * @var Authorization|null
     */
    private $authorization;

    public function __construct(?Authorization $authorization = null)
    {
        $this->authorization = $authorization;
    }

    public function getAuthorization(): Authorization
    {
        return $this->authorization;
    }
}
