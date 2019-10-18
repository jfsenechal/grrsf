<?php

namespace App\Entity\Security;

use App\Doctrine\Traits\IdEntityTrait;
use App\Doctrine\Traits\TimestampableEntityTrait;
use App\Entity\Area;
use App\Entity\Room;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="authorization", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"user_id", "area_id"}),
 *     @ORM\UniqueConstraint(columns={"user_id", "room_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\Security\AuthorizationRepository")
 * @UniqueEntity(fields={"user", "area"}, message="Ce user est déjà lié au domaine")
 * @UniqueEntity(fields={"user", "room"}, message="Ce user est déjà lié à la room")
 */
class Authorization
{
    use IdEntityTrait;
    use TimestampableEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Security\User", inversedBy="authorizations")
     * @ORM\JoinColumn(nullable=false)
     * @var \App\Entity\Security\User
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="authorizations")
     * @ORM\JoinColumn(nullable=true)
     * @var \App\Entity\Area|null
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="authorizations")
     * @ORM\JoinColumn(nullable=true)
     * @var \App\Entity\Room|null
     */
    private $room;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isAreaAdministrator;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isResourceAdministrator;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->isAreaAdministrator = false;
        $this->isResourceAdministrator = false;
    }

    public function __toString(): string
    {
        return '';
    }

    public function getIsAreaAdministrator(): ?bool
    {
        return $this->isAreaAdministrator;
    }

    public function setIsAreaAdministrator(bool $isAreaAdministrator): self
    {
        $this->isAreaAdministrator = $isAreaAdministrator;

        return $this;
    }

    public function getIsResourceAdministrator(): ?bool
    {
        return $this->isResourceAdministrator;
    }

    public function setIsResourceAdministrator(bool $isResourceAdministrator): self
    {
        $this->isResourceAdministrator = $isResourceAdministrator;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
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
