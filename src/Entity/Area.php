<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Doctrine\Traits\IdEntityTrait;
use App\Doctrine\Traits\NameEntityTrait;
use App\Entity\Security\Authorization;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Area.
 *
 * @ORM\Table(name="area")
 * @ORM\Entity(repositoryClass="App\Repository\AreaRepository")
 * @ApiResource()
 */
class Area
{
    use IdEntityTrait, NameEntityTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $orderDisplay;

    /**
     * @var int
     * @Assert\LessThan(propertyPath="endTime", message="area.constraint.start_smaller_end")
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $startTime;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $endTime;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $weekStart;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $is24HourFormat;
    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     */
    private $daysOfWeekToDisplay;

    /**
     * Intervalle de temps.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $timeInterval;

    /**
     * Durée maximum qu'un utilisateur peut réserver.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $durationMaximumEntry;

    /**
     * Durée par défaut d'une réservation.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $durationDefaultEntry;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $minutesToAddToEndTime;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $maxBooking;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isRestricted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Room", mappedBy="area")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Security\Authorization", mappedBy="area", orphanRemoval=true)
     */
    private $authorizations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\EntryType")
     */
    private $entryTypes;

    public function __construct()
    {
        $this->startTime = 8;
        $this->endTime = 19;
        $this->is24HourFormat = true;
        $this->orderDisplay = 0;
        $this->weekStart = 0;
        $this->daysOfWeekToDisplay = [1, 2, 3, 4, 5];
        $this->timeInterval = 30;
        $this->durationDefaultEntry = 30;
        $this->durationMaximumEntry = -1;
        $this->minutesToAddToEndTime = 0;
        $this->maxBooking = -1;
        $this->isRestricted = false;
        $this->rooms = new ArrayCollection();
        $this->authorizations = new ArrayCollection();
        $this->entryTypes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function getStartTime(): ?int
    {
        return $this->startTime;
    }

    public function setStartTime(int $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?int
    {
        return $this->endTime;
    }

    public function setEndTime(int $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getWeekStart(): ?int
    {
        return $this->weekStart;
    }

    public function setWeekStart(int $weekStart): self
    {
        $this->weekStart = $weekStart;

        return $this;
    }

    public function getIs24HourFormat(): ?bool
    {
        return $this->is24HourFormat;
    }

    public function setIs24HourFormat(bool $is24HourFormat): self
    {
        $this->is24HourFormat = $is24HourFormat;

        return $this;
    }

    public function getDaysOfWeekToDisplay(): ?array
    {
        return $this->daysOfWeekToDisplay;
    }

    public function setDaysOfWeekToDisplay(array $daysOfWeekToDisplay): self
    {
        $this->daysOfWeekToDisplay = $daysOfWeekToDisplay;

        return $this;
    }

    public function getTimeInterval(): ?int
    {
        return $this->timeInterval;
    }

    public function setTimeInterval(int $timeInterval): self
    {
        $this->timeInterval = $timeInterval;

        return $this;
    }

    public function getDurationMaximumEntry(): ?int
    {
        return $this->durationMaximumEntry;
    }

    public function setDurationMaximumEntry(int $durationMaximumEntry): self
    {
        $this->durationMaximumEntry = $durationMaximumEntry;

        return $this;
    }

    public function getDurationDefaultEntry(): ?int
    {
        return $this->durationDefaultEntry;
    }

    public function setDurationDefaultEntry(int $durationDefaultEntry): self
    {
        $this->durationDefaultEntry = $durationDefaultEntry;

        return $this;
    }

    public function getMinutesToAddToEndTime(): ?int
    {
        return $this->minutesToAddToEndTime;
    }

    public function setMinutesToAddToEndTime(int $minutesToAddToEndTime): self
    {
        $this->minutesToAddToEndTime = $minutesToAddToEndTime;

        return $this;
    }

    public function getMaxBooking(): ?int
    {
        return $this->maxBooking;
    }

    public function setMaxBooking(int $maxBooking): self
    {
        $this->maxBooking = $maxBooking;

        return $this;
    }

    public function getIsRestricted(): ?bool
    {
        return $this->isRestricted;
    }

    public function setIsRestricted(bool $isRestricted): self
    {
        $this->isRestricted = $isRestricted;

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->setArea($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->contains($room)) {
            $this->rooms->removeElement($room);
            // set the owning side to null (unless already changed)
            if ($room->getArea() === $this) {
                $room->setArea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Authorization[]
     */
    public function getAuthorizations(): Collection
    {
        return $this->authorizations;
    }

    public function addAuthorization(Authorization $authorization): self
    {
        if (!$this->authorizations->contains($authorization)) {
            $this->authorizations[] = $authorization;
            $authorization->setArea($this);
        }

        return $this;
    }

    public function removeAuthorization(Authorization $authorization): self
    {
        if ($this->authorizations->contains($authorization)) {
            $this->authorizations->removeElement($authorization);
            // set the owning side to null (unless already changed)
            if ($authorization->getArea() === $this) {
                $authorization->setArea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EntryType[]
     */
    public function getEntryTypes(): Collection
    {
        return $this->entryTypes;
    }

    public function addEntryType(EntryType $entryType): self
    {
        if (!$this->entryTypes->contains($entryType)) {
            $this->entryTypes[] = $entryType;
        }

        return $this;
    }

    public function removeEntryType(EntryType $entryType): self
    {
        if ($this->entryTypes->contains($entryType)) {
            $this->entryTypes->removeElement($entryType);
        }

        return $this;
    }
}
