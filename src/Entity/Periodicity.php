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
 *
 * @AppAssertPeriodicity\Periodicity()
 * @AppAssertPeriodicity\PeriodicityEveryDay()
 * @AppAssertPeriodicity\PeriodicityEveryMonth()
 * @AppAssertPeriodicity\PeriodicityEveryYear()
 * @AppAssertPeriodicity\PeriodicityEveryWeek()
 */
class Periodicity
{
    use IdEntityTrait;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_time;

    /**
     * Every month, every day, every...
     *
     * @see PeriodicityConstant::getTypesPeriodicite
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $week_repeat;

    /**
     * Monday, tuesday, wednesday...
     *
     * @see DateProvider::getNamesDaysOfWeek();
     *
     * @var int[]
     * @ORM\Column(type="array", nullable=true)
     */
    private $week_days;

    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Entry", mappedBy="periodicity")
     */
    private $entries;

    /**
     * Use for validator form
     * @var Entry|null
     */
    private $entry_reference;

    public function __construct(?Entry $entry = null)
    {
        $this->type = 0;
        $this->week_days = [];
        $this->entries = new ArrayCollection();
        $this->entry_reference = $entry;
    }

    /**
     * @return Entry|null
     */
    public function getEntryReference(): ?Entry
    {
        return $this->entry_reference;
    }

    /**
     * @param Entry|null $entry_reference
     */
    public function setEntryReference(?Entry $entry_reference): void
    {
        $this->entry_reference = $entry_reference;
    }

    /**
     * @return array
     */
    public function getWeekDays(): array
    {
        return $this->week_days;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getWeekRepeat(): ?int
    {
        return $this->week_repeat;
    }

    public function setWeekRepeat(?int $week_repeat): self
    {
        $this->week_repeat = $week_repeat;

        return $this;
    }

    public function setWeekDays(array $week_days): self
    {
        $this->week_days = $week_days;

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
