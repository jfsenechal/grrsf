<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrrUtilisateurs
 *
 * @ORM\Table(name="grr_utilisateurs")
 * @ORM\Entity(repositoryClass="App\Repository\GrrUtilisateursRepository")
 */
class GrrUtilisateurs
{
 //   use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom = '';

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=false)
     */
    private $prenom = '';

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32, nullable=false)
     */
    private $password = '';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email = '';

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=30, nullable=false)
     */
    private $statut = '';

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=20, nullable=false)
     */
    private $etat = '';

    /**
     * @var int
     *
     * @ORM\Column(name="default_site", type="smallint", nullable=false)
     */
    private $defaultSite = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="default_area", type="smallint", nullable=false)
     */
    private $defaultArea = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="default_room", type="smallint", nullable=false)
     */
    private $defaultRoom = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="default_style", type="string", length=50, nullable=false)
     */
    private $defaultStyle = '';

    /**
     * @var string
     *
     * @ORM\Column(name="default_list_type", type="string", length=50, nullable=false)
     */
    private $defaultListType = '';

    /**
     * @var string
     *
     * @ORM\Column(name="default_language", type="string", length=3, nullable=false, options={"fixed"=true})
     */
    private $defaultLanguage = '';

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=10, nullable=false, options={"default"="local"})
     */
    private $source = 'local';

    public function getLogin(): ?string
    {
        return $this->login;
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

    public function getPassword(): ?string
    {
        return $this->password;
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


}
