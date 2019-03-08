<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\GrrArea;
use App\Factory\GrrAreaFactory;
use App\Repository\GrrRoomRepository;

class GrrAreaManager
{

    public function __construct(GrrAreaFactory $areaFactory)
    {
    }

    public function persist(GrrArea $grrArea)
    {
        $this->grrRoomFactory->persist($grrArea);
    }

    public function remove(GrrArea $grrArea)
    {
        $this->grrRoomFactory->remove($grrArea);
    }

    public function flush()
    {
        $this->grrRoomFactory->flush();
    }

    public function insert(GrrArea $grrArea)
    {
        $this->grrRoomFactory->insert($grrArea);
    }

}