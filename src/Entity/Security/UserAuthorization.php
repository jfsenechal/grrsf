<?php

namespace App\Entity\Security;

use App\Doctrine\Traits\IdEntityTrait;
use App\Doctrine\Traits\TimestampableEntityTrait;
use App\Entity\Area;
use App\Entity\Room;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user_authorization", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"user_id", "area_id"}),
 *     @ORM\UniqueConstraint(columns={"user_id", "room_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\Security\AuthorizationRepository")
 * @UniqueEntity(fields={"user", "area"}, message="Ce user est déjà lié au domaine")
 * @UniqueEntity(fields={"user", "room"}, message="Ce user est déjà lié à la room")
 */
class UserAuthorization
{
    use IdEntityTrait;
    use TimestampableEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Security\User", inversedBy="authorizations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="authorizations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="authorizations")
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
    private $is_resource_administrator;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->is_area_administrator = false;
        $this->is_resource_administrator = false;
    }

    public function __toString()
    {
        return '';
    }

    public function getIsAreaAdministrator(): ?bool
    {
        return $this->is_area_administrator;
    }

    public function setIsAreaAdministrator(bool $is_area_administrator): self
    {
        $this->is_area_administrator = $is_area_administrator;

        return $this;
    }

    public function getIsResourceAdministrator(): ?bool
    {
        return $this->is_resource_administrator;
    }

    public function setIsResourceAdministrator(bool $is_resource_administrator): self
    {
        $this->is_resource_administrator = $is_resource_administrator;

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
