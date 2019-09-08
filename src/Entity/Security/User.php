<?php

namespace App\Entity\Security;

use App\Doctrine\IdEntityTrait;
use App\Entity\Area;
use App\Entity\Room;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="grr_user", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 *     @ORM\UniqueConstraint(columns={"username"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\Security\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Un utilisateur a déjà cette adresse email")
 * @UniqueEntity(fields={"username"}, message="Un utilisateur a déjà ce nom d'utilisateur")
 */
class User implements UserInterface
{
    use IdEntityTrait;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string|null The hashed password
     * @ORM\Column(type="string", nullable=true)
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
     * @ORM\Column(type="boolean")
     */
    private $is_enabled;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $language_default;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area")
     */
    private $area_default;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room")
     */
    private $room_default;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Security\UserAuthorization", mappedBy="user", orphanRemoval=true)
     */
    private $authorizations;

    public function __construct()
    {
        $this->is_enabled = true;
        $this->authorizations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function addRole(string $role): self
    {
        //   if (!$this->roles->contains($role)) {
        $this->roles[] = $role;

        // }

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Alias
     * @return Area|null
     */
    public function getArea(): ?Area
    {
        return $this->area_default;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return (string)$this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
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
     * @return Collection|UserAuthorization[]
     */
    public function getAuthorizations(): Collection
    {
        return $this->authorizations;
    }

    public function addAuthorization(UserAuthorization $authorization): self
    {
        if (!$this->authorizations->contains($authorization)) {
            $this->authorizations[] = $authorization;
            $authorization->setUser($this);
        }

        return $this;
    }

    public function removeAuthorization(UserAuthorization $authorization): self
    {
        if ($this->authorizations->contains($authorization)) {
            $this->authorizations->removeElement($authorization);
            // set the owning side to null (unless already changed)
            if ($authorization->getUser() === $this) {
                $authorization->setUser(null);
            }
        }

        return $this;
    }

    public function getLanguageDefault(): ?string
    {
        return $this->language_default;
    }

    public function setLanguageDefault(?string $language_default): self
    {
        $this->language_default = $language_default;

        return $this;
    }

}
