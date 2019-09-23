<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

use App\Entity\Room;

class RoomManager extends BaseManager
{
    public function removeEntries(Room $room)
    {
        foreach ($room->getEntries() as $entry) {
            $this->entityManager->remove($entry);
        }
    }
}
