<?php

namespace App\Entity;

use App\Doctrine\Traits\IdEntityTrait;
use App\Periodicity\PeriodicityConstant;
use App\Validator\Periodicity as AppAssertPeriodicity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="periodicity")
 * @ORM\Entity(repositoryClass="App\Repository\PeriodicityRepository")
 *
 * @AppAssertPeriodicity\Periodicity
 * @AppAssertPeriodicity\PeriodicityEveryDay
 * @AppAssertPeriodicity\PeriodicityEveryMonth
 * @AppAssertPeriodicity\PeriodicityEveryYear
 * @AppAssertPeriodicity\PeriodicityEveryWeek
 */
class Periodicity
{
    use IdEntityTrait;

    /**
     * @ORM\Column(type="date")
     *
     * @var \DateTimeInterface
     */
    private $endTime;

    /**
     * Every month, every day, every...
     *
     * @see PeriodicityConstant::getTypesPeriodicite
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $weekRepeat;

    /**
     * Monday, tuesday, wednesday...
     *
     * @see DateProvider::getNamesDaysOfWeek();
     *
     * @var int[]
     * @ORM\Column(type="array", nullable=true)
     */
    private $weekDays;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entry", mappedBy="periodicity")
     *
     * @var \App\Entity\Entry[]|\Doctrine\Common\Collections\Collection
     */
    private $entries;

    /**
     * Use for validator form.
     *
     * @var Entry|null
     */
    private $entryReference;

    public function __construct(?Entry $entry = null)
    {
        $this->type = 0;
        $this->weekDays = [];
        $this->entries = new ArrayCollection();
        $this->entryReference = $entry;
    }

    public function getEntryReference(): ?Entry
    {
        return $this->entryReference;
    }

    public function setEntryReference(?Entry $entry_reference): void
    {
        $this->entryReference = $entry_reference;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getWeekRepeat(): ?int
    {
        return $this->weekRepeat;
    }

    public function setWeekRepeat(?int $weekRepeat): self
    {
        $this->weekRepeat = $weekRepeat;

        return $this;
    }

    public function getWeekDays(): ?array
    {
        return $this->weekDays;
    }

    public function setWeekDays(?array $weekDays): self
    {
        $this->weekDays = $weekDays;

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
            $entry->setPeriodicity($this);
        }

        return $this;
    }

    public function removeEntry(Entry $entry): self
    {
        if ($this->entries->contains($entry)) {
            $this->entries->removeElement($entry);
            // set the owning side to null (unless already changed)
            if ($entry->getPeriodicity() === $this) {
                $entry->setPeriodicity(null);
            }
        }

        return $this;
    }
}
