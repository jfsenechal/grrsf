<?php

namespace App\Entity;

use App\Doctrine\IdEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="grr_periodicity_days")
 * @ORM\Entity(repositoryClass="App\Repository\PeriodicityDayRepository")
 */
class PeriodicityDay
{
   use IdEntityTrait;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $date_periodicity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entry", inversedBy="periodicityDays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entry;

    public function getDatePeriodicity(): ?\DateTimeImmutable
    {
        return $this->date_periodicity;
    }

    public function setDatePeriodicity(\DateTimeImmutable $date_periodicity): self
    {
        $this->date_periodicity = $date_periodicity;

        return $this;
    }

    public function getEntry(): ?Entry
    {
        return $this->entry;
    }

    public function setEntry(?Entry $entry): self
    {
        $this->entry = $entry;

        return $this;
    }
}
