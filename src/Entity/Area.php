<?php

namespace App\Entity;

use App\Doctrine\IdEntityTrait;
use App\Entity\Security\UserManagerResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Area.
 *
 * @ORM\Table(name="grr_area")
 * @ORM\Entity(repositoryClass="App\Repository\AreaRepository")
 */
class Area
{
    use IdEntityTrait;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default": 0})
     */
    private $is_private = false;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false, options={"default: 0"})
     */
    private $order_display;

    /**
     * @var int
     *
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
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $is_24_hour_format;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     */
    private $days_of_week_to_display = [];

    /**
     * Durée de la tranche horaire
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $duration_time_slot;

    /**
     * Durée maximum qu'un utilisateur peut réserver
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"default"="-1"})
     */
    private $duration_maximum_entry;

    /**
     * Durée par défaut d'une réservation
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $duration_default_entry = 900;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false, options={"default:0"})
     */
    private $minutes_to_add_to_end_time;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false, options={"default"="-1"})
     */
    private $max_booking;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Room", mappedBy="area")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Security\UserManagerResource", mappedBy="area", orphanRemoval=true)
     */
    private $users_manager_resource;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->managerAreas = new ArrayCollection();
        $this->users_manager_resource = new ArrayCollection();
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

    public function getIsPrivate(): ?bool
    {
        return $this->is_private;
    }

    public function setIsPrivate(bool $is_private): self
    {
        $this->is_private = $is_private;

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

    public function getIs24HourFormat(): ?int
    {
        return $this->is_24_hour_format;
    }

    public function setIs24HourFormat(int $is_24_hour_format): self
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

    public function getDurationTimeSlot(): ?int
    {
        return $this->duration_time_slot;
    }

    public function setDurationTimeSlot(int $duration_time_slot): self
    {
        $this->duration_time_slot = $duration_time_slot;

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
     * @return Collection|UserManagerResource[]
     */
    public function getUsersManagerResource(): Collection
    {
        return $this->users_manager_resource;
    }

    public function addUsersManagerResource(UserManagerResource $usersManagerResource): self
    {
        if (!$this->users_manager_resource->contains($usersManagerResource)) {
            $this->users_manager_resource[] = $usersManagerResource;
            $usersManagerResource->setArea($this);
        }

        return $this;
    }

    public function removeUsersManagerResource(UserManagerResource $usersManagerResource): self
    {
        if ($this->users_manager_resource->contains($usersManagerResource)) {
            $this->users_manager_resource->removeElement($usersManagerResource);
            // set the owning side to null (unless already changed)
            if ($usersManagerResource->getArea() === $this) {
                $usersManagerResource->setArea(null);
            }
        }

        return $this;
    }


}
