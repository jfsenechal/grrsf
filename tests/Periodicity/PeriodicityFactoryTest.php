<?php

namespace App\Tests\Periodicity;

use App\Area\AreaFactory;
use App\Entity\Entry;
use App\Entity\Periodicity;
use App\Entry\EntryFactory;
use App\Periodicity\PeriodicityFactory;
use App\Room\RoomFactory;
use App\Tests\BaseTesting;

class PeriodicityFactoryTest extends BaseTesting
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
    /**
     * @var PeriodicityFactory
     */
    private $periodicityFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $periodicityFactory = new PeriodicityFactory();
        $this->entryFactory = new EntryFactory($periodicityFactory);
        $this->periodicityFactory = new PeriodicityFactory();
        $this->areaFactory = new AreaFactory();
        $this->roomFactory = new RoomFactory();
    }

    /**
     * @dataProvider getData
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testNew(
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute
    ) {
        $this->loadFixtures();

        $area = $this->getArea('Esquare');
        $room = $this->getRoom('Box');

        $entry = $this->entryFactory->initEntryForNew($area, $room, $year, $month, $day, $hour, $minute);
        $entry->setName('Test');
        $entry->setCreatedBy('Test');

        $this->assertInstanceOf(Entry::class, $entry);
        $periodicity = $this->periodicityFactory->createNew($entry);
        $periodicity->setEndTime(new \DateTime('+3 days'));

        $this->assertInstanceOf(Periodicity::class, $entry->getPeriodicity());
        $this->assertInstanceOf(Entry::class, $periodicity->getEntryReference());
        $this->assertSame('Test', $periodicity->getEntryReference()->getName());
    }

    public function getData()
    {
        return [
            [2019, 8, 19, 10, 12],
            [2019, 2, 28, 9, 24],
            [2019, 12, 31, 14, 30],
            [2019, 6, 30, 16, 20],
        ];
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'user.yaml',
                $this->pathFixtures.'authorization.yaml',
            ];

        $this->loader->load($files);
    }
}
