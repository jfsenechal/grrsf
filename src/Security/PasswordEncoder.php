<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 11/03/19
 * Time: 20:40.
 */

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoder
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function encodePassword(User $user, string $password)
    {
        $this->userPasswordEncoder->encodePassword($user, $password);
    }
}
