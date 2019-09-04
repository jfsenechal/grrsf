<?php

namespace App\Events;

use App\Model\AuthorizationModel;
use Symfony\Contracts\EventDispatcher\Event;

class AuthorizationModelEvent extends Event
{
    const NEW_SUCCESS = 'grr.authorization.model.new.success';
    const EDIT_SUCCESS = 'grr.authorization.model.edit.success';
    const DELETE_SUCCESS = 'grr.authorization.model.delete.success';

    /**
     * @var AuthorizationModel
     */
    private $authorizationModel;

    public function __construct(AuthorizationModel $authorizationModel)
    {
        $this->authorizationModel = $authorizationModel;
    }

    /**
     * @return AuthorizationModel
     */
    public function getAuthorizationModel(): AuthorizationModel
    {
        return $this->authorizationModel;
    }
}
