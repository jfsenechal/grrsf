<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Factory;

use App\Entity\Security\User;

class UserFactory
{
    public function createNew(): User
    {
        return new User();
    }
}
