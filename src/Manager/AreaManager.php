<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\Area;
use App\Repository\AreaRepository;

class AreaManager
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomManager
     */
    private $roomManager;

    public function __construct(AreaRepository $areaRepository, RoomManager $roomManager)
    {
        $this->areaRepository = $areaRepository;
        $this->roomManager = $roomManager;
    }

    public function persist(Area $area)
    {
        $this->areaRepository->persist($area);
    }

    public function remove(Area $area)
    {
        $this->areaRepository->remove($area);
    }

    public function flush()
    {
        $this->areaRepository->flush();
    }

    public function insert(Area $area)
    {
        $this->areaRepository->insert($area);
    }

    public function removeRooms(Area $area) {
        foreach ($area->getRooms() as $room) {
            $this->roomManager->remove($room);
        }
        $this->roomManager->flush();
    }

}