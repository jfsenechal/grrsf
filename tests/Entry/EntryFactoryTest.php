<?php

namespace App\Tests\Entry;

use DateTimeInterface;
use App\Area\AreaFactory;
use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use App\Entry\EntryFactory;
use App\Periodicity\PeriodicityFactory;
use App\Room\RoomFactory;
use App\Tests\BaseTesting;
use Carbon\Carbon;

class EntryFactoryTest extends BaseTesting
{
    /**
     * @var EntryFactory
     */
    private $entryFactory;
    /**
     * @var AreaFactory
     */
    private $areaFactory;
    /**
     * @var RoomFactory
     */
    private $roomFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $periodicityFactory = new PeriodicityFactory();
        $this->entryFactory = new EntryFactory($periodicityFactory);
        $this->areaFactory = new AreaFactory();
        $this->roomFactory = new RoomFactory();
    }

    public function testCreateNew(): void
    {
        $entry = $this->entryFactory->createNew();
        $this->assertInstanceOf(Entry::class, $entry);
    }

    /**
     * @dataProvider getData
     *
     * @param Area $area
     * @param Room $room
     * @param int  $year
     * @param int  $month
     * @param int  $day
     * @param int  $hour
     * @param int  $minute
     */
    public function testInitEntryForNew(
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute
    ): void {
        $area = $this->areaFactory->createNew();
        $area->setName('Area1');
        $room = $this->roomFactory->createNew($area);
        $room->setName('Salle1');

        $date = Carbon::create($year, $month, $day, $hour, $minute);
        $endTime = $date->copy()->addMinutes($area->getDurationDefaultEntry());

        $entry = $this->entryFactory->initEntryForNew($area, $room, $year, $month, $day, $hour, $minute);

        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertSame('Area1', $entry->getArea()->getName());
        $this->assertSame('Salle1', $entry->getRoom()->getName());
        $this->assertInstanceOf(DateTimeInterface::class, $entry->getStartTime());
        $this->assertInstanceOf(DateTimeInterface::class, $entry->getEndTime());
        $this->assertSame("$year $month $day $hour $minute", $entry->getStartTime()->format('Y n j G i'));
        $this->assertSame($endTime->format('Y n j G i'), $entry->getEndTime()->format('Y n j G i'));
    }

    public function getData(): array
    {
        return [
            [2019, 8, 19, 10, 12],
            [2019, 2, 28, 9, 24],
            [2019, 12, 31, 14, 30],
            [2019, 6, 30, 16, 20],
        ];
    }
}
