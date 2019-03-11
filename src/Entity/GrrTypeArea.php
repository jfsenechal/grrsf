<?php

namespace App\Entity;

use App\Doctrine\IdEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * GrrTypeArea
 *
 * @ORM\Table(name="grr_type_area")
 * @ORM\Entity(repositoryClass="App\Repository\GrrTypeAreaRepository")
 */
class GrrTypeArea
{
    use IdEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="type_name", type="string", length=30, nullable=false)
     */
    private $typeName;

    /**
     * @var int
     *
     * @ORM\Column(name="order_display", type="smallint", nullable=false)
     */
    private $orderDisplay;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur", type="string", nullable=true)
     */
    private $couleur;

    /**
     * @var string
     *
     * @ORM\Column(name="type_letter", type="string", length=2, nullable=false)
     */
    private $typeLetter;

    /**
     * @var int
     *
     * @ORM\Column(name="disponible", type="smallint", nullable=false)
     */
    private $disponible;

    public function __toString()
    {
        return $this->typeName;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): self
    {
        $this->typeName = $typeName;

        return $this;
    }

    public function getOrderDisplay(): ?int
    {
        return $this->orderDisplay;
    }

    public function setOrderDisplay(int $orderDisplay): self
    {
        $this->orderDisplay = $orderDisplay;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getTypeLetter(): ?string
    {
        return $this->typeLetter;
    }

    public function setTypeLetter(string $typeLetter): self
    {
        $this->typeLetter = $typeLetter;

        return $this;
    }

    public function getDisponible(): ?int
    {
        return $this->disponible;
    }

    public function setDisponible(int $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }
}
