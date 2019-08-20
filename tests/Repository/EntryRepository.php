<?php


namespace App\Tests\Repository;

use App\Entity\Entry;
use App\Entity\Room;
use App\Model\Month;
use Carbon\Carbon;

class EntryRepository extends BaseRepository
{

    public function testFindForMonth()
    {
        $month = Month::init(2019, 8, 1);

        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->findForMonth($month);

        $count = count($entries);

        $this->assertEquals(4, $count);
    }

    public function findForWeek()
    {
        $day = Carbon::today();
        $room = new Room();

        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->findForWeek($day, $room);

        $count = count($entries);

        $this->assertEquals(4, $count);
    }

    public function findForDay()
    {
        $day = Carbon::today();
        $room = new Room();
        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->findForDay($day, $room);

        $count = count($entries);

        $this->assertEquals(4, $count);
    }

    public function isBusy(Entry $entry, Room $room)
    {

    }

}
