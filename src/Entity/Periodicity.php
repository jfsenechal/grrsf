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
     * @ORM\Column(type="boolean")
     */
    private $every_day = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $every_year = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $every_week = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $number_week = 0;

    /**
     * @var ArrayCollection
     * @ORM\Column(type="array")
     */
    private $week_days;

    /**
     * @ORM\Column(type="boolean")
     */
    private $every_month_same_day = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $every_month_same_week_of_day = false;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Entry", mappedBy="periodicity", cascade={"persist", "remove"})
     */
    private $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
        $this->week_days = new ArrayCollection();
    }

    public function addWeekDay(int $day): self
    {
        if (!$this->week_days->contains($day)) {
            $this->week_days[] = $day;
        }

        return $this;
    }

    public function removeWeekDay(int $day): self
    {
        if ($this->week_days->contains($day)) {
            $this->week_days->removeElement($day);
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
        $newPeriodicity = $entry === null ? null : $this;
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

    public function getEveryDay(): ?bool
    {
        return $this->every_day;
    }

    public function setEveryDay(bool $every_day): self
    {
        $this->every_day = $every_day;

        return $this;
    }

    public function getEveryYear(): ?bool
    {
        return $this->every_year;
    }

    public function setEveryYear(bool $every_year): self
    {
        $this->every_year = $every_year;

        return $this;
    }

    public function getEveryWeek(): ?bool
    {
        return $this->every_week;
    }

    public function setEveryWeek(bool $every_week): self
    {
        $this->every_week = $every_week;

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

    public function getWeekDays(): ?Collection
    {
        return $this->week_days;
    }

    public function setWeekDays(array $week_days): self
    {
        $this->week_days = $week_days;

        return $this;
    }

    public function getEveryMonthSameDay(): ?bool
    {
        return $this->every_month_same_day;
    }

    public function setEveryMonthSameDay(bool $every_month_same_day): self
    {
        $this->every_month_same_day = $every_month_same_day;

        return $this;
    }

    public function getEveryMonthSameWeekOfDay(): ?bool
    {
        return $this->every_month_same_week_of_day;
    }

    public function setEveryMonthSameWeekOfDay(bool $every_month_same_week_of_day): self
    {
        $this->every_month_same_week_of_day = $every_month_same_week_of_day;

        return $this;
    }

}
