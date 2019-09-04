<?php

namespace App\Model;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use Doctrine\Common\Collections\ArrayCollection;

class AuthorizationModel
{
    /**
     * @var Area|null
     */
    protected $area;

    /**
     * @var Room[]|array
     */
    protected $rooms;

    /**
     * @var User[]|array
     */
    protected $users;

    /**
     * @var int|null $role
     */
    private $role;

    public function __construct()
    {
        $this->users = [];
        $this->rooms = new ArrayCollection();
    }

    /**
     * @return Area|null
     */
    public function getArea(): ?Area
    {
        return $this->area;
    }

    /**
     * @param Area|null $area
     */
    public function setArea(?Area $area): void
    {
        $this->area = $area;
    }

    /**
     * @return Room[]|ArrayCollection
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @param Room[]|array $rooms
     */
    public function setRooms(array $rooms): void
    {
        $this->rooms = $rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->contains($room)) {
            $this->rooms->removeElement($room);
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRole(): ?int
    {
        return $this->role;
    }

    /**
     * @param int|null $role
     */
    public function setRole(?int $role): void
    {
        $this->role = $role;
    }

    /**
     * @return User[]|array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param User[]|array
     */
    public function setUsers(array $users): void
    {
        $this->users = $users;
    }
}
