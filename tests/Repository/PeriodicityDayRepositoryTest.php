<?php


namespace App\Tests\Repository;

use App\Entity\Area;
use App\Model\Month;
use App\Repository\PeriodicityDayRepository;

class PeriodicityDayRepositoryTest extends BaseRepository
{
    public function testFindForMonth()
    {
        $monthModel = new Month();
        $room = $this->getRoom('');

        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
            ]
        );

        $area = $this->entityManager
            ->getRepository(PeriodicityDayRepository::class)
            ->findForMonth($monthModel, $room);

        $this->assertEquals('Esquare', $area->getName());
    }

    public function testFindForDay()
    {
        //CarbonInterface $day, Room $room
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
        //CarbonInterface $day, Room $room
    }

}
