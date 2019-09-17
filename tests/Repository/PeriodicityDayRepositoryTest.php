<?php

namespace App\Tests\Repository;

use App\Entity\PeriodicityDay;
use App\Model\Month;
use App\Tests\BaseTesting;
use Carbon\CarbonImmutable;

class PeriodicityDayRepositoryTest extends BaseTesting
{
    /**
     * @dataProvider getData
     */
    public function testfindForMonth(
        int $year,
        int $month,
        string $areaName,
        int $count,
        array $daysResult,
        ?string $roomName = null
    ) {

        $this->loadFixtures();

        $monthModel = Month::init($year, $month);
        $room = null;

        if ($roomName) {
            $room = $this->getRoom($roomName);
        }

        $area = $this->getArea($areaName);

        $days = $this->entityManager
            ->getRepository(PeriodicityDay::class)
            ->findForMonth($monthModel->firstOfMonth(), $area, $room);

        self::assertCount($count, $days);

        foreach ($days as $day) {
            self::assertContains($day->getDatePeriodicity()->format('Y-m-d'), $daysResult);
            self::assertSame($areaName, $day->getEntry()->getRoom()->getArea()->getName());
        }
    }

    public function getData()
    {
        yield [
            2019,
            6,
            'Esquare',
            1,
            ['2019-06-05'],
            'Digital Room',
        ];

        yield [
            2017,
            3,
            'Hdv',
            4,
            ['2017-03-08', '2017-03-11', '2017-03-22', '2017-03-25'],
            null,
        ];
    }

    public function testFindForDay()
    {
        $this->loadFixtures();

        $day = CarbonImmutable::create(2017, 02, 11);

        $room = $this->getRoom('Salle cafétaria');

        $days = $this->entityManager
            ->getRepository(PeriodicityDay::class)
            ->findForDay($day->toDateTime(), $room);

        foreach ($days as $day) {
            self::assertSame('2017-02-11', $day->getDatePeriodicity()->format('Y-m-d'));
            self::assertSame('Toutes les 2 semaines, mercredi et samedi', $day->getEntry()->getName());
        }
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'entry_with_periodicity.yaml',
                $this->pathFixtures.'periodicity.yaml',
                $this->pathFixtures.'periodicity_day.yaml',
            ];

        $this->loader->load($files);
    }
}
