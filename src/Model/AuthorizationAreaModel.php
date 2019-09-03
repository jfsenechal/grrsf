<?php

namespace App\Model;

use App\Entity\Area;
use App\Entity\Security\User;

class AuthorizationAreaModel
{
    /**
     * @var Area|null
     */
    protected $area;

    /**
     * @var User[]|array
     */
    protected $users;

    /**
     * @var bool|null
     */
    private $area_administrator;

    /**
     * @var int $role
     */
    private $role;

    public function __construct()
    {
        $this->area_administrator = false;
        $this->role = 0;
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
    public function isAreaAdministrator(): bool
    {
        return $this->area_administrator;
    }

    /**
     * @param bool $area_administrator
     */
    public function setAreaAdministrator(bool $area_administrator): void
    {
        $this->area_administrator = $area_administrator;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @param int $role
     */
    public function setRole(int $role): void
    {
        $this->role = $role;
    }

}
