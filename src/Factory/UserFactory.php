<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    public function createNew(): User
    {
        return new User();
    }

    public function setDefaultValues(User $Utilisateur)
    {
        $Utilisateur
            ->setDefaultSite(0)
            ->setDefaultArea(0)
            ->setDefaultRoom(0)
            ->setSource('local');
    }
}