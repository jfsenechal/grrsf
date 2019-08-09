<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
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
    /**
     * @var EntryManager
     */
    private $entryManager;

    public function __construct(RoomRepository $roomRepository, EntryManager $entryManager)
    {
        $this->roomRepository = $roomRepository;
        $this->entryManager = $entryManager;
    }

    public function persist(Room $room)
    {
        $this->roomRepository->persist($room);
    }

    public function remove(Room $room)
    {
        $this->roomRepository->remove($room);
    }

    public function flush()
    {
        $this->roomRepository->flush();
    }

    public function insert(Room $room)
    {
        $this->roomRepository->insert($room);
    }

    public function removeEntries(Room $room)
    {
        foreach ($room->getEntries() as $entry) {
            $this->entryManager->remove($entry);
        }
    }
}
