<?php

namespace App\Model;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use Doctrine\Common\Collections\ArrayCollection;

class AuthorizationResourceModel
{
    /**
     * @var Area|null
     */
    protected $area;

    /**
     * @var Room[]|ArrayCollection
     */
    protected $rooms;

    /**
     * @var User[]|array
     */
    protected $users;

    /**
     * @var bool
     */
    private $resource_administrator;

    public function __construct()
    {
        $this->resource_administrator = false;
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
     * @return User[]|array
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User[]|array $users
     */
    public function setUsers($users): void
    {
        $this->users = $users;
    }

    /**
     * @return bool
     */
    public function isResourceAdministrator(): bool
    {
        return $this->resource_administrator;
    }

    /**
     * @param bool $resource_administrator
     */
    public function setResourceAdministrator(bool $resource_administrator): void
    {
        $this->resource_administrator = $resource_administrator;
    }

}
