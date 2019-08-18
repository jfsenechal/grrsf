<?php

namespace App\Entity;

use App\Doctrine\IdEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TypeArea
 * //depracted : grr_type_area.
 *
 * @ORM\Table(name="grr_entry_type")
 * @ORM\Entity(repositoryClass="App\Repository\EntryTypeRepository")
 */
class EntryType
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entry", mappedBy="type")
     */
    private $entries;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
        $this->orderDisplay = 0;
        $this->disponible = 2;
    }

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

    /**
     * @return Collection|Entry[]
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry): self
    {
        if (!$this->entries->contains($entry)) {
            $this->entries[] = $entry;
            $entry->setType($this);
        }

        return $this;
    }

    public function removeEntry(Entry $entry): self
    {
        if ($this->entries->contains($entry)) {
            $this->entries->removeElement($entry);
            // set the owning side to null (unless already changed)
            if ($entry->getType() === $this) {
                $entry->setType(null);
            }
        }

        return $this;
    }
}
