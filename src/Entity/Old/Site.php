<?php

namespace App\Entity\Old;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site.
 *
 * @ORM\Table(name="grr_site")
 * @ORM\Entity
 */
class Site
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sitecode", type="string", length=10, nullable=true)
     */
    private $sitecode;

    /**
     * @var string
     *
     * @ORM\Column(name="sitename", type="string", length=50, nullable=false)
     */
    private $sitename = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse_ligne1", type="string", length=38, nullable=true)
     */
    private $adresseLigne1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse_ligne2", type="string", length=38, nullable=true)
     */
    private $adresseLigne2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse_ligne3", type="string", length=38, nullable=true)
     */
    private $adresseLigne3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cp", type="string", length=5, nullable=true)
     */
    private $cp;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ville", type="string", length=50, nullable=true)
     */
    private $ville;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pays", type="string", length=50, nullable=true)
     */
    private $pays;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tel", type="string", length=25, nullable=true)
     */
    private $tel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fax", type="string", length=25, nullable=true)
     */
    private $fax;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSitecode(): ?string
    {
        return $this->sitecode;
    }

    public function setSitecode(?string $sitecode): self
    {
        $this->sitecode = $sitecode;

        return $this;
    }

    public function getSitename(): ?string
    {
        return $this->sitename;
    }

    public function setSitename(string $sitename): self
    {
        $this->sitename = $sitename;

        return $this;
    }

    public function getAdresseLigne1(): ?string
    {
        return $this->adresseLigne1;
    }

    public function setAdresseLigne1(?string $adresseLigne1): self
    {
        $this->adresseLigne1 = $adresseLigne1;

        return $this;
    }

    public function getAdresseLigne2(): ?string
    {
        return $this->adresseLigne2;
    }

    public function setAdresseLigne2(?string $adresseLigne2): self
    {
        $this->adresseLigne2 = $adresseLigne2;

        return $this;
    }

    public function getAdresseLigne3(): ?string
    {
        return $this->adresseLigne3;
    }

    public function setAdresseLigne3(?string $adresseLigne3): self
    {
        $this->adresseLigne3 = $adresseLigne3;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }
}
