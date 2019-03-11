<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 19:59
 */

namespace App\Manager;

use App\Entity\GrrRoom;
use App\Repository\GrrRoomRepository;

class GrrRoomManager
{
    /**
     * @var GrrRoomRepository
     */
    private $grrRoomRepository;

    public function __construct(GrrRoomRepository $grrRoomRepository)
    {
        $this->grrRoomRepository = $grrRoomRepository;
    }

    public function persist(GrrRoom $grrRoom)
    {
        $this->grrRoomRepository->persist($grrRoom);
    }

    public function remove(GrrRoom $grrRoom)
    {
        $this->grrRoomRepository->remove($grrRoom);
    }

    public function flush()
    {
        $this->grrRoomRepository->flush();
    }

    public function insert(GrrRoom $grrRoom)
    {
        $this->grrRoomRepository->insert($grrRoom);
    }

}