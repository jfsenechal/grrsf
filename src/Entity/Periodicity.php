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
    private $repeat_week = 0;

    /**
     * @var Collection
     * @ORM\Column(type="array")
     */
    private $days;

    public function __construct()
    {
        $this->days = new ArrayCollection();
        $this->end_time = new \DateTime();
    }

    public function addDay(int $day): self
    {
        if (!$this->days->contains($day)) {
            $this->days[] = $day;
        }

        return $this;
    }

    public function removeDay(int $day): self
    {
        if ($this->days->contains($day)) {
            $this->days->removeElement($day);
        }

        return $this;
    }

    /**
     * @return Collection|array
     */
    public function getDays(): Collection
    {
        return $this->days;
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

    public function getRepeatWeek(): ?int
    {
        return $this->repeat_week;
    }

    public function setRepeatWeek(int $repeat_week): self
    {
        $this->repeat_week = $repeat_week;

        return $this;
    }

    public function se2tDays(array $days): self
    {
        $this->days = $days;

        return $this;
    }

}
