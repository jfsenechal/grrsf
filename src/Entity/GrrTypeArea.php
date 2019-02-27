<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrrTypeArea
 *
 * @ORM\Table(name="grr_type_area")
 * @ORM\Entity(repositoryClass="App\Repository\GrrTypeAreaRepository")
 */
class GrrTypeArea
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
     * @var string
     *
     * @ORM\Column(name="type_name", type="string", length=30, nullable=false)
     */
    private $typeName = '';

    /**
     * @var int
     *
     * @ORM\Column(name="order_display", type="smallint", nullable=false)
     */
    private $orderDisplay = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="couleur", type="smallint", nullable=false)
     */
    private $couleur = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="type_letter", type="string", length=2, nullable=false, options={"fixed"=true})
     */
    private $typeLetter = '';

    /**
     * @var string
     *
     * @ORM\Column(name="disponible", type="string", length=1, nullable=false, options={"default"="2"})
     */
    private $disponible = '2';

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCouleur(): ?int
    {
        return $this->couleur;
    }

    public function setCouleur(int $couleur): self
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

    public function getDisponible(): ?string
    {
        return $this->disponible;
    }

    public function setDisponible(string $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }


}
