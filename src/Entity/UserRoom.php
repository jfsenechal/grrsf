<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserRoom
 *
 * @ORM\Table(name="grr_j_user_room")
 * @ORM\Entity(repositoryClass="App\Repository\UserRoomRepository")
 */
class UserRoom
{
    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $login = '';

    /**
     * @var int
     *
     * @ORM\Column(name="id_room", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idRoom = '0';

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getIdRoom(): ?int
    {
        return $this->idRoom;
    }


}
