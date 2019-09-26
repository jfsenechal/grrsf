<?php

namespace App\Entity;

use App\Doctrine\Traits\IdEntityTrait;
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
 */
class Area
{
    use IdEntityTrait;

    /**
     * @var string
     * @Assert\NotBlank
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
     * @var int
     * @Assert\LessThan(propertyPath="end_time", message="area.constraint.start_smaller_end")
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $start_time;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $end_time;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $week_start;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $is_24_hour_format;
    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     */
    private $days_of_week_to_display;

    /**
     * Intervalle de temps.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $time_interval;

    /**
     * Durée maximum qu'un utilisateur peut réserver.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $duration_maximum_entry;

    /**
     * Durée par défaut d'une réservation.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $duration_default_entry;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $minutes_to_add_to_end_time;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $max_booking;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $is_restricted;

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
        $this->start_time = 8;
        $this->end_time = 19;
        $this->is_24_hour_format = true;
        $this->order_display = 0;
        $this->week_start = 0;
        $this->days_of_week_to_display = [1,2,3,4,5];
        $this->time_interval = 30;
        $this->duration_default_entry = 30;
        $this->duration_maximum_entry = -1;
        $this->minutes_to_add_to_end_time = 0;
        $this->max_booking = -1;
        $this->is_restricted = false;
        $this->rooms = new ArrayCollection();
        $this->authorizations = new ArrayCollection();
        $this->entryTypes = new ArrayCollection();
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

    public function getStartTime(): ?int
    {
        return $this->start_time;
    }

    public function setStartTime(int $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?int
    {
        return $this->end_time;
    }

    public function setEndTime(int $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getWeekStart(): ?int
    {
        return $this->week_start;
    }

    public function setWeekStart(int $week_start): self
    {
        $this->week_start = $week_start;

        return $this;
    }

    public function getIs24HourFormat(): ?bool
    {
        return $this->is_24_hour_format;
    }

    public function setIs24HourFormat(bool $is_24_hour_format): self
    {
        $this->is_24_hour_format = $is_24_hour_format;

        return $this;
    }

    public function getDaysOfWeekToDisplay(): ?array
    {
        return $this->days_of_week_to_display;
    }

    public function setDaysOfWeekToDisplay(array $days_of_week_to_display): self
    {
        $this->days_of_week_to_display = $days_of_week_to_display;

        return $this;
    }

    public function getTimeInterval(): ?int
    {
        return $this->time_interval;
    }

    public function setTimeInterval(int $time_interval): self
    {
        $this->time_interval = $time_interval;

        return $this;
    }

    public function getDurationMaximumEntry(): ?int
    {
        return $this->duration_maximum_entry;
    }

    public function setDurationMaximumEntry(int $duration_maximum_entry): self
    {
        $this->duration_maximum_entry = $duration_maximum_entry;

        return $this;
    }

    public function getDurationDefaultEntry(): ?int
    {
        return $this->duration_default_entry;
    }

    public function setDurationDefaultEntry(int $duration_default_entry): self
    {
        $this->duration_default_entry = $duration_default_entry;

        return $this;
    }

    public function getMinutesToAddToEndTime(): ?int
    {
        return $this->minutes_to_add_to_end_time;
    }

    public function setMinutesToAddToEndTime(int $minutes_to_add_to_end_time): self
    {
        $this->minutes_to_add_to_end_time = $minutes_to_add_to_end_time;

        return $this;
    }

    public function getMaxBooking(): ?int
    {
        return $this->max_booking;
    }

    public function setMaxBooking(int $max_booking): self
    {
        $this->max_booking = $max_booking;

        return $this;
    }

    public function getIsRestricted(): ?bool
    {
        return $this->is_restricted;
    }

    public function setIsRestricted(bool $is_restricted): self
    {
        $this->is_restricted = $is_restricted;

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
