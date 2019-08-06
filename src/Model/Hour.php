<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 28/03/19
 * Time: 14:49
 */

namespace App\Model;


use Doctrine\Common\Collections\ArrayCollection;

class Hour
{
    /**
     * @var \DateTimeInterface
     */
    protected $begin;
    /**
     * @var \DateTimeInterface
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
     * @return \DateTimeInterface
     */
    public function getBegin(): \DateTimeInterface
    {
        return $this->begin;
    }

    /**
     * @param \DateTimeInterface $begin
     */
    public function setBegin(\DateTimeInterface $begin): void
    {
        $this->begin = $begin;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    /**
     * @param \DateTimeInterface $end
     */
    public function setEnd(\DateTimeInterface $end): void
    {
        $this->end = $end;
    }


}