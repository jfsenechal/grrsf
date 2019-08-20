<?php


namespace App\Tests\Repository;

use App\Entity\Area;
use App\Entity\Room;

class RoomRepositoryTest extends BaseRepository
{
    public function testSearchByCategoryName()
    {
        $room = $this->entityManager
            ->getRepository(Room::class)
            ->findOneBy(['name' => 'Salle Conseil']);

        $this->assertEquals('Salle Conseil', $room->getName());
    }

    public function testFindByArea()
    {
        $area = $this->entityManager
            ->getRepository(Area::class)
            ->findOneBy(['name' => 'E-square']);

        $rooms = $this->entityManager
            ->getRepository(Room::class)
            ->findByArea($area);

        $this->assertEquals(5, count($rooms));
    }
}
