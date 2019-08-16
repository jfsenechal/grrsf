<?php

namespace App\Entity;

use App\Doctrine\IdEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="grr_periodicity")
 * @ORM\Entity(repositoryClass="App\Repository\PeriodicityRepository")
 */
class Periodicity
{
   use IdEntityTrait;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_time;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $repeat_week;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=true)
     */
    private $days;

    /**
     * @var Entry
     * @ORM\OneToOne(targetEntity="App\Entity\Entry", mappedBy="periodicity")
     */
    private $entry;

    public function __construct()
    {
        $this->days = [];
    }

    /**
     * @return array
     */
    public function getDays(): array
    {
        return $this->days;
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

    public function getRepeatWeek(): ?int
    {
        return $this->repeat_week;
    }

    public function setRepeatWeek(?int $repeat_week): self
    {
        $this->repeat_week = $repeat_week;

        return $this;
    }

    public function setDays(array $days): self
    {
        $this->days = $days;

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

}
