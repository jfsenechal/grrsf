<?php


namespace App\Tests\Repository;

use App\Entity\Area;

class PeriodicityDayRepositoryTest extends BaseRepository
{
    public function testFindForMonth()
    {
        //Month $monthModel, Room $room = null

        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
            ]
        );

        $area = $this->entityManager
            ->getRepository(Area::class)
            ->findOneBy(['name' => 'Esquare']);

        $this->assertEquals('Esquare', $area->getName());
    }

    public function testFindForDay()
    {
        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
            ]
        );

        $result = $this->entityManager->getRepository(Area::class)->findAll();

        $this->assertEquals(12, count($result));
    }

    public function testfindFoWeek()
    {

    }

}
