<?php

namespace App\Model;

use App\Entity\Entry;
use App\Entity\Room;
use Doctrine\Common\Collections\ArrayCollection;

class RoomModel
{
    /**
     * @var Room
     */
    protected $room;

    /**
     * @var ArrayCollection|Day[]
     */
    protected $data_days;
    /**
     * @var ArrayCollection|Entry[]
     */
    private $entries;

    public function __construct(Room $room)
    {
        $this->room = $room;
        $this->data_days = new ArrayCollection();
        $this->entries = new ArrayCollection();
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function addDataDay(Day $day): self
    {
        if (!$this->data_days->contains($day)) {
            $this->data_days[] = $day;
        }

        return $this;
    }

    /**
     * @return Day[]|ArrayCollection
     */
    public function getDataDays()
    {
        return $this->data_days;
    }

    /**
     * @return Day[]|ArrayCollection
     */
    public function getEntries()
    {
        return $this->entries;
    }

    public function setEntries($entries)
    {
        $this->entries = $entries;
    }
}
