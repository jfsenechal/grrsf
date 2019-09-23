<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59.
 */

namespace App\Manager;

use App\Entity\Area;
use Doctrine\ORM\EntityManagerInterface;

class AreaManager extends BaseManager
{
    /**
     * @var RoomManager
     */
    private $roomManager;

    public function __construct(EntityManagerInterface $entityManager, RoomManager $roomManager)
    {
        parent::__construct($entityManager);
        $this->entityManager = $entityManager;
        $this->roomManager = $roomManager;
    }

    public function removeRooms(Area $area)
    {
        foreach ($area->getRooms() as $room) {
            $this->roomManager->remove($room);
        }
        $this->flush();
    }
}
