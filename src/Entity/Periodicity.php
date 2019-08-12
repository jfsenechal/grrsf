<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="grr_periodicity")
 * @ORM\Entity(repositoryClass="App\Repository\PeriodicityRepository")
 */
class Periodicity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_time;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $number_week = 0;

    /**
     * @var ArrayCollection
     * @ORM\Column(type="array")
     */
    private $weeks;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Entry", mappedBy="periodicity", cascade={"persist", "remove"})
     */
    private $entry;

    public function __construct()
    {
        $this->weeks = new ArrayCollection();
    }

    public function addWeekDay(int $day): self
    {
        if (!$this->weeks->contains($day)) {
            $this->weeks[] = $day;
        }

        return $this;
    }

    public function removeWeekDay(int $day): self
    {
        if ($this->weeks->contains($day)) {
            $this->weeks->removeElement($day);
        }

        return $this;
    }

    public function getEntry(): ?Entry
    {
        return $this->entry;
    }

    public function setEntry(?Entry $entry): self
    {
        $this->entry = $entry;

        // set (or unset) the owning side of the relation if necessary
        $newPeriodicity = null === $entry ? null : $this;
        if ($newPeriodicity !== $entry->getPeriodicity()) {
            $entry->setPeriodicity($newPeriodicity);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNumberWeek(): ?int
    {
        return $this->number_week;
    }

    public function setNumberWeek(int $number_week): self
    {
        $this->number_week = $number_week;

        return $this;
    }

    public function getWeeks(): ?Collection
    {
        return $this->weeks;
    }

    public function setWeeks(array $weeks): self
    {
        $this->weeks = $weeks;

        return $this;
    }

}
