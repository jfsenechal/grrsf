<?php

namespace App\Tests\Repository;

use App\Entity\Entry;
use App\Model\Month;
use App\Tests\BaseTesting;
use Carbon\Carbon;

class EntryRepositoryTest extends BaseTesting
{
    public function testFindForMonth()
    {
        $this->loadFixtures();

        $month = Month::init(2019, 8, 1);
        $area = $this->getArea('Esquare');

        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->findForMonth($month->toDateTime(), $area);

        $count = count($entries);

        $this->assertEquals(4, $count);
    }

    /**
     * @dataProvider dataForDay
     */
    public function testFindForDay(int $year, int $month, int $day, string $room, int $count, string $title)
    {
        $this->loadFixtures();

        $day = Carbon::create($year, $month, $day);
        $room = $this->getRoom($room);

        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->findForDay($day, $room);

        $countResult = count($entries);
        $this->assertEquals($count, $countResult);

        $this->assertSame($title, $entries[0]->getName());
    }

    /**
     * La date de debut de la nouvelle entry est plus grande que
     * la date de début d'une entry existante.
     */
    public function testBusyDateBeginGreaterThanStartTime()
    {
        $this->loadFixtures();
        $entriesBusy = $this->dataForBusy();
        $entry = $entriesBusy[0];

        //pour avoir un room existant en db
        $room = $this->getRoom($entry->getRoom()->getName());

        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->isBusy($entry, $room);

        foreach ($entries as $result) {
            $this->assertSame('Réunion henalux', $result->getName());
        }
    }

    /**
     * La date de fin de la nouvelle entry est plus petite que
     * la date de fin d'une entry existante.
     */
    public function testBusyDateEndIsSmallerThanEndTime()
    {
        $this->loadFixtures();
        $entriesBusy = $this->dataForBusy();
        $entry = $entriesBusy[1];

        //pour avoir un room existant en db
        $room = $this->getRoom($entry->getRoom()->getName());

        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->isBusy($entry, $room);

        foreach ($entries as $result) {
            $this->assertSame('Réunion détente', $result->getName());
        }
    }

    /**
     * @return Entry[]
     */
    private function dataForBusy()
    {
        $objets = $this->loaderSimple->loadFiles(
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'entry_busy.yaml',
            ]
        );

        $entries = [];

        foreach ($objets->getObjects() as $object) {
            if ($object instanceof Entry) {
                $entries[] = $object;
            }
        }

        return $entries;
    }

    public function dataForDay()
    {
        return [
            [
                'year' => 2019,
                'month' => 8,
                'day' => 20,
                'room' => 'Relax Room',
                'count' => 1,
                'title' => 'Réunion pssp',
            ],
            [
                'year' => 2019,
                'month' => 9,
                'day' => 19,
                'room' => 'Salle Conseil',
                'count' => 1,
                'title' => 'Réunion conseillers',
            ],
        ];
    }

    protected function loadFixtures($withBusy = false)
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'entry.yaml',
            ];

        if ($withBusy) {
            $files = [$this->pathFixtures.'entry_busy.yaml'];
        }

        $this->loader->load($files);
    }
}
