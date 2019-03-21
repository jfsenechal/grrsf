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
    private $grrRoomRepository;

    public function __construct(RoomRepository $grrRoomRepository)
    {
        $this->grrRoomRepository = $grrRoomRepository;
    }

    public function persist(Room $grrRoom)
    {
        $this->grrRoomRepository->persist($grrRoom);
    }

    public function remove(Room $grrRoom)
    {
        $this->grrRoomRepository->remove($grrRoom);
    }

    public function flush()
    {
        $this->grrRoomRepository->flush();
    }

    public function insert(Room $grrRoom)
    {
        $this->grrRoomRepository->insert($grrRoom);
    }

}