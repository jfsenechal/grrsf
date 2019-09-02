<?php

namespace App\Entity\Security;

use App\Doctrine\IdEntityTrait;
use App\Entity\Area;
use App\Entity\Room;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="grr_manager_resource", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"user_id", "area_id"}),
 *     @ORM\UniqueConstraint(columns={"user_id", "room_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\Security\UserManagerResourceRepository")
 * @UniqueEntity(fields={"user", "area"}, message="Ce user est déjà lié au domaine")
 * @UniqueEntity(fields={"user", "room"}, message="Ce user est déjà lié au domaine")
 */
class UserManagerResource
{
    use IdEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Security\User", inversedBy="users_manager_resource")
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
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $is_area_administrator;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $is_area_manager;

    public function __construct()
    {
        $this->is_area_administrator = false;
        $this->is_area_manager = false;
    }

    public function __toString()
    {
        return '';
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

    /**
     * @return bool
     */
    public function isIsAreaAdministrator(): bool
    {
        return $this->is_area_administrator;
    }

    /**
     * @param bool $is_area_administrator
     */
    public function setIsAreaAdministrator(bool $is_area_administrator): void
    {
        $this->is_area_administrator = $is_area_administrator;
    }

    /**
     * @return bool
     */
    public function isIsAreaManager(): bool
    {
        return $this->is_area_manager;
    }

    /**
     * @param bool $is_area_manager
     */
    public function setIsAreaManager(bool $is_area_manager): void
    {
        $this->is_area_manager = $is_area_manager;
    }

    public function getIsAreaAdministrator(): ?bool
    {
        return $this->is_area_administrator;
    }

    public function getIsAreaManager(): ?bool
    {
        return $this->is_area_manager;
    }
}
