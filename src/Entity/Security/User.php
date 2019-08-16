<?php

namespace App\Entity\Security;

use App\Doctrine\IdEntityTrait;
use App\Entity\Area;
use App\Entity\Room;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="grr_user")
 * @ORM\Entity(repositoryClass="App\Repository\Security\UserRepository")
 */
class User implements UserInterface
{
    use IdEntityTrait;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $first_name;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    private $is_enabled = true;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area")
     */
    private $area_default;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room")
     */
    private $room_default;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Security\UserManagerResource", mappedBy="user", orphanRemoval=true)
     */
    private $users_manager_resource;

    public function __construct()
    {
        $this->managerAreas = new ArrayCollection();
        $this->users_manager_resource = new ArrayCollection();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_GRR
        $roles[] = 'ROLE_GRR';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    public function getAreaDefault(): ?Area
    {
        return $this->area_default;
    }

    public function setAreaDefault(?Area $area_default): self
    {
        $this->area_default = $area_default;

        return $this;
    }

    public function getRoomDefault(): ?Room
    {
        return $this->room_default;
    }

    public function setRoomDefault(?Room $room_default): self
    {
        $this->room_default = $room_default;

        return $this;
    }

    /**
     * @return Collection|UserManagerResource[]
     */
    public function getUsersManagerResource(): Collection
    {
        return $this->users_manager_resource;
    }

    public function addUsersManagerResource(UserManagerResource $usersManagerResource): self
    {
        if (!$this->users_manager_resource->contains($usersManagerResource)) {
            $this->users_manager_resource[] = $usersManagerResource;
            $usersManagerResource->setUser($this);
        }

        return $this;
    }

    public function removeUsersManagerResource(UserManagerResource $usersManagerResource): self
    {
        if ($this->users_manager_resource->contains($usersManagerResource)) {
            $this->users_manager_resource->removeElement($usersManagerResource);
            // set the owning side to null (unless already changed)
            if ($usersManagerResource->getUser() === $this) {
                $usersManagerResource->setUser(null);
            }
        }

        return $this;
    }

    /**
     * STOP
     */
    
}
