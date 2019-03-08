<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\GrrArea;
use App\Entity\GrrRoom;
use App\Factory\GrrAreaFactory;
use App\Factory\GrrRoomFactory;

class GrrRoomManager
{
    /**
     * @var GrrRoomFactory
     */
    private $grrRoomFactory;

    public function __construct(GrrRoomFactory $grrRoomFactory)
    {
        $this->grrRoomFactory = $grrRoomFactory;
    }

    public function persist(GrrRoom $grrRoom)
    {
        $this->grrRoomFactory->persist($grrRoom);
    }

    public function remove(GrrRoom $grrRoom)
    {
        $this->grrRoomFactory->remove($grrRoom);
    }

    public function flush()
    {
        $this->grrRoomFactory->flush();
    }

    public function insert(GrrRoom $grrRoom)
    {
        $this->grrRoomFactory->insert($grrRoom);
    }

}