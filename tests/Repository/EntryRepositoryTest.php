<?php


namespace App\Tests\Repository;

use App\Entity\Entry;
use App\Entity\Room;
use App\Model\Month;
use Carbon\Carbon;

class EntryRepositoryTest extends BaseRepository
{
    public function testFindForMonth()
    {
        $this->loadFixtures();

        $month = Month::init(2019, 8, 1);

        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->findForMonth($month);

        $count = count($entries);

        $this->assertEquals(4, $count);
    }

    /**
     * @dataProvider dataForDay
     */
    public function testFindByDayAndRoom(int $year, int $month, int $day, string $room, int $count, string $title)
    {
        $this->loadFixtures();

        $day = Carbon::create($year, $month, $day);
        $room = $this->getRoom($room);

        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->findByDayAndRoom($day, $room);

        $count = count($entries);
        $this->assertEquals($count, $count);
        var_dump(array_column($entries,'name'));
        $this->assertSame($title, $entries[0]->getName());
    }

    public function isBusy(Entry $entry, Room $room)
    {

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


    protected function loadFixtures()
    {
        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entryType.yaml',
                $this->pathFixtures.'entry.yaml',
            ]
        );
    }

}
