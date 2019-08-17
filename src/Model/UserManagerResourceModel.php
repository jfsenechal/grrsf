<?php

namespace App\Model;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;

class UserManagerResourceModel
{
    /**
     * 1 => administrator
     * 2 => manager
     * @var int|null $area_level
     */
    private $area_level;

    /**
     * @var Area|null $area
     */
    protected $area;

    /**
     * @var Room[]|array $rooms
     */
    protected $rooms;

    /**
     * @var User[]|array $rooms
     */
    protected $users;

    /**
     * @return int|null
     */
    public function getAreaLevel(): ?int
    {
        return $this->area_level;
    }

    /**
     * @param int|null $area_level
     */
    public function setAreaLevel(?int $area_level): void
    {
        $this->area_level = $area_level;
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
     * @return Room[]|array
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @param Room[]|array $rooms
     */
    public function setRooms( $rooms): void
    {
        $this->rooms = $rooms;
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
    public function setUsers( $users): void
    {
        $this->users = $users;
    }

}