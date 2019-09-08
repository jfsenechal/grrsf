<?php

namespace App\Entity;

use App\Doctrine\Traits\IdEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="grr_entry_type", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"letter"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\EntryTypeRepository")
 * @UniqueEntity(fields={"letter"}, message="entry_type.constraint.already_use")
 */
class EntryType
{
    use IdEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $order_display;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2, nullable=false, unique=true)
     */
    private $letter;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $disponible;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entry", mappedBy="type")
     */
    private $entries;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
        $this->order_display = 0;
        $this->disponible = 2;
    }

    public function __toString()
    {
        return $this->name;
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

    public function getOrderDisplay(): ?int
    {
        return $this->order_display;
    }

    public function setOrderDisplay(int $order_display): self
    {
        $this->order_display = $order_display;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getLetter(): ?string
    {
        return $this->letter;
    }

    public function setLetter(string $letter): self
    {
        $this->letter = $letter;

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
