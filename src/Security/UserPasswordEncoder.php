<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 8/03/19
 * Time: 18:30.
 */

namespace App\Security;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserPasswordEncoder
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function changePassword(UserInterface $user, string $clearPassword)
    {
        $this->userPasswordEncoder->encodePassword($user, $clearPassword);
    }
}
