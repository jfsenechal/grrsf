<?php

namespace App\Tests\Repository;

use App\Entity\PeriodicityDay;
use App\Model\Month;
use Carbon\CarbonImmutable;

class PeriodicityDayRepositoryTest extends BaseRepository
{
    public function testFindForMonth()
    {
        $this->loadFixtures();

        $monthModel = Month::init(2019, 6);
        $room = $this->getRoom('Digital Room');

        $days = $this->entityManager
            ->getRepository(PeriodicityDay::class)
            ->findForMonth($monthModel, $room);

        foreach ($days as $day) {
            self::assertSame('2019-06-05', $day->getDatePeriodicity()->format('Y-m-d'));
            self::assertSame('Tous les mois le 5', $day->getEntry()->getName());
        }

        $room = null;
        $result = [
            [
                'Tous les mois le 5',
                '2019-06-05',
            ],
            [
                'Tous les mois le 2ième mercredi',
                '2019-06-12',
            ],
        ];
        $days = $this->entityManager
            ->getRepository(PeriodicityDay::class)
            ->findForMonth($monthModel, $room);

        $i = 0;
        foreach ($days as $day) {
            self::assertSame($result[$i][0], $day->getEntry()->getName());
            self::assertSame($result[$i][1], $day->getDatePeriodicity()->format('Y-m-d'));
            ++$i;
        }
    }

    public function testFindForDay()
    {
        $this->loadFixtures();

        $day = CarbonImmutable::create(2017, 02, 11);

        $room = $this->getRoom('Salle cafétaria');

        $days = $this->entityManager
            ->getRepository(PeriodicityDay::class)
            ->findForDay($day, $room);

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
                $this->pathFixtures.'periodicity_day.yaml',
            ];

        $this->loader->load($files);
    }
}
