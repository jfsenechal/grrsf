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
        $month = Month::init(2019, 8, 1);

        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->findForMonth($month);

        $count = count($entries);

        $this->assertEquals(4, $count);
    }

    public function testFindForDay()
    {
        $day = Carbon::today();
        $room = $this->getRoom('Relax Room');

        $entries = $this->entityManager
            ->getRepository(Entry::class)
            ->findByDayAndRoom($day, $room);

        $count = count($entries);
        $this->assertEquals(1, $count);
        $this->assertSame('Réunion pssp', $entries[0]->getName());
        foreach ($entries as $entry) {
            //  var_dump($entry->getName());
        }
    }

    public function isBusy(Entry $entry, Room $room)
    {

    }

    protected function getRoom(string $roomName): Room
    {
        return $this->entityManager
            ->getRepository(Room::class)
            ->findOneBy(['name' => $roomName]);
    }

}
