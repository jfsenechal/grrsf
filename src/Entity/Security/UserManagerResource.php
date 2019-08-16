<?php

namespace App\Entity\Security;

use App\Doctrine\IdEntityTrait;
use App\Entity\Area;
use App\Entity\Room;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="grr_manager_resource")
 * @ORM\Entity(repositoryClass="App\Repository\Security\AdministratorResourceRepository")
 */
class UserManagerResource
{
   use IdEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Security\User", inversedBy="managerAreas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="users_manager_resource")
     * @ORM\JoinColumn(nullable=true)
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="users_manager_resource")
     * @ORM\JoinColumn(nullable=true)
     */
    private $room;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $is_area_administrator = false;

    public function getIsAreaAdministrator(): ?bool
    {
        return $this->is_area_administrator;
    }

    public function setIsAreaAdministrator(bool $is_area_administrator): self
    {
        $this->is_area_administrator = $is_area_administrator;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArea(): ?Area
    {
        return $this->area;
    }

    public function setArea(?Area $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

}
