<?php

namespace App\Entity;

use App\Doctrine\IdEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="grr_utilisateurs")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    use IdEntityTrait;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    private $prenom;

    /**
     * @var string The hashed password
     *
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $etat;

    /**
     * @var int
     *
     * @ORM\Column(name="default_site", type="smallint", nullable=true)
     */
    private $defaultSite;

    /**
     * @var int
     *
     * @ORM\Column(name="default_area", type="smallint", nullable=true)
     */
    private $defaultArea;

    /**
     * @var int
     *
     * @ORM\Column(name="default_room", type="smallint", nullable=true)
     */
    private $defaultRoom;

    /**
     * @var string
     *
     * @ORM\Column(name="default_style", type="string", length=50, nullable=true)
     */
    private $defaultStyle;

    /**
     * @var string
     *
     * @ORM\Column(name="default_list_type", type="string", length=50, nullable=true)
     */
    private $defaultListType;

    /**
     * @var string
     *
     * @ORM\Column(name="default_language", type="string", length=3, nullable=true)
     */
    private $defaultLanguage;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $source;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];


    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return $this->login;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function setPassword(string $password): self
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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDefaultSite(): ?int
    {
        return $this->defaultSite;
    }

    public function setDefaultSite(int $defaultSite): self
    {
        $this->defaultSite = $defaultSite;

        return $this;
    }

    public function getDefaultArea(): ?int
    {
        return $this->defaultArea;
    }

    public function setDefaultArea(int $defaultArea): self
    {
        $this->defaultArea = $defaultArea;

        return $this;
    }

    public function getDefaultRoom(): ?int
    {
        return $this->defaultRoom;
    }

    public function setDefaultRoom(int $defaultRoom): self
    {
        $this->defaultRoom = $defaultRoom;

        return $this;
    }

    public function getDefaultStyle(): ?string
    {
        return $this->defaultStyle;
    }

    public function setDefaultStyle(string $defaultStyle): self
    {
        $this->defaultStyle = $defaultStyle;

        return $this;
    }

    public function getDefaultListType(): ?string
    {
        return $this->defaultListType;
    }

    public function setDefaultListType(string $defaultListType): self
    {
        $this->defaultListType = $defaultListType;

        return $this;
    }

    public function getDefaultLanguage(): ?string
    {
        return $this->defaultLanguage;
    }

    public function setDefaultLanguage(string $defaultLanguage): self
    {
        $this->defaultLanguage = $defaultLanguage;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
