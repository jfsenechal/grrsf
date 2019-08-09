<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 28/03/19
 * Time: 14:49.
 */

namespace App\Model;

use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;

class TimeSlot
{
    /**
     * @var CarbonInterface
     */
    protected $begin;
    /**
     * @var CarbonInterface
     */
    protected $end;

    /**
     * @var ArrayCollection|Day[]
     */
    protected $data_days;

    /**
     * @var ArrayCollection|RoomModel[]
     */
    protected $rooms;

    public function __construct()
    {
        $this->data_days = new ArrayCollection();
        $this->rooms = new ArrayCollection();
    }

    /**
     * @return RoomModel[]|ArrayCollection
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    public function addRoom(RoomModel $roomModel): self
    {
        if (!$this->rooms->contains($roomModel)) {
            $this->rooms[] = $roomModel;
        }

        return $this;
    }

    public function setRooms(iterable $rooms)
    {
        $this->rooms = $rooms;
    }

    /**
     * @return CarbonInterface
     */
    public function getBegin(): CarbonInterface
    {
        return $this->begin;
    }

    /**
     * @param CarbonInterface $begin
     */
    public function setBegin(CarbonInterface $begin): void
    {
        $this->begin = $begin;
    }

    /**
     * @return CarbonInterface
     */
    public function getEnd(): CarbonInterface
    {
        return $this->end;
    }

    /**
     * @param CarbonInterface $end
     */
    public function setEnd(CarbonInterface $end): void
    {
        $this->end = $end;
    }
}
