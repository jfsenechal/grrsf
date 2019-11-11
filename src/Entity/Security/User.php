<?php

namespace App\Entity\Security;

use App\Doctrine\Traits\IdEntityTrait;
use App\Doctrine\Traits\NameEntityTrait;
use App\Doctrine\Traits\TimestampableEntityTrait;
use App\Entity\Area;
use App\Entity\Room;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user", uniqueConstraints={
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
    use TimestampableEntityTrait;
    use NameEntityTrait;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @var string
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @var mixed[]
     */
    private $roles = [];

    /**
     * @var string|null The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @var string|null
     */
    private $first_name;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isEnabled;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $languageDefault;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area")
     * @var Area
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room")
     * @var Room
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Security\Authorization", mappedBy="user", orphanRemoval=true)
     * @var Authorization[]
     */
    private $authorizations;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->isEnabled = true;
        $this->authorizations = new ArrayCollection();
    }

    public function __toString()
    {
        return mb_strtoupper($this->name).' '.$this->first_name;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): ?array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_GRR
        $roles[] = 'ROLE_GRR';

        return array_unique($roles);
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        if (in_array($role, $this->roles, true)) {
            $index = array_search($role, $this->roles);
            unset($this->roles[$index]);
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        if (in_array($role, $this->getRoles(), true)) {
            return true;
        }

        return false;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
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
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $is_enabled): self
    {
        $this->isEnabled = $is_enabled;

        return $this;
    }

    /**
     * @return Collection|Authorization[]
     */
    public function getAuthorizations(): Collection
    {
        return $this->authorizations;
    }

    public function addAuthorization(Authorization $authorization): self
    {
        if (!$this->authorizations->contains($authorization)) {
            $this->authorizations[] = $authorization;
            $authorization->setUser($this);
        }

        return $this;
    }

    public function removeAuthorization(Authorization $authorization): self
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
        return $this->languageDefault;
    }

    public function setLanguageDefault(?string $languageDefault): self
    {
        $this->languageDefault = $languageDefault;

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
