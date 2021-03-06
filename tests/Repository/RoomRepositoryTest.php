<?php

namespace App\Tests\Repository;

use App\Entity\Area;
use App\Entity\Room;
use App\Tests\BaseTesting;
use Doctrine\ORM\QueryBuilder;

class RoomRepositoryTest extends BaseTesting
{
    public function testSearchByName(): void
    {
        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
            ]
        );

        $room = $this->entityManager
            ->getRepository(Room::class)
            ->findOneBy(['name' => 'Salle Conseil']);

        $this->assertEquals('Salle Conseil', $room->getName());
    }

    public function testFindByArea(): void
    {
        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
            ]
        );
        $area = $this->entityManager
            ->getRepository(Area::class)
            ->findOneBy(['name' => 'Esquare']);

        $rooms = $this->entityManager
            ->getRepository(Room::class)
            ->findByArea($area);

        $this->assertEquals(5, count($rooms));
    }

    public function getRoomsByAreaQueryBuilder(): void
    {
        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
            ]
        );

        $area = $this->entityManager
            ->getRepository(Area::class)
            ->findOneBy(['name' => 'Hdv']);

        $builder = $this->entityManager
            ->getRepository(Room::class)
            ->getRoomsByAreaQueryBuilder($area);

        $this->assertInstanceOf(QueryBuilder::class, $builder);
    }
}
