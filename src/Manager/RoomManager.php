<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\Room;
use App\Repository\RoomRepository;

class RoomManager
{
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->grrRoomRepository = $roomRepository;
    }

    public function persist(Room $room)
    {
        $this->grrRoomRepository->persist($room);
    }

    public function remove(Room $room)
    {
        $this->grrRoomRepository->remove($room);
    }

    public function flush()
    {
        $this->grrRoomRepository->flush();
    }

    public function insert(Room $room)
    {
        $this->grrRoomRepository->insert($room);
    }

}