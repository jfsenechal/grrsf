<?php

namespace App\Entity;

use App\Doctrine\IdEntityTrait;
use App\Periodicity\PeriodicityConstant;
use Doctrine\ORM\Mapping as ORM;
use App\Validator as AppAssert;

/**
 * @ORM\Table(name="grr_periodicity")
 * @ORM\Entity(repositoryClass="App\Repository\PeriodicityRepository")
 *
 *
 * @AppAssert\Periodicity()
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
     * @ORM\Column(type="integer", nullable=true)
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
     * @var Entry
     * @ORM\OneToOne(targetEntity="App\Entity\Entry", mappedBy="periodicity")
     */
    private $entry;

    public function __construct()
    {
        $this->week_days = [];
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
}
