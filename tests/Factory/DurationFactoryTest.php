<?php

namespace App\Tests\Factory;

use DateTime;
use DateTimeInterface;
use App\Area\AreaFactory;
use App\Entry\EntryFactory;
use App\Factory\DurationFactory;
use App\Model\DurationModel;
use App\Periodicity\PeriodicityFactory;
use App\Room\RoomFactory;
use App\Tests\BaseTesting;

class DurationFactoryTest extends BaseTesting
{
    /**
     * @var DurationFactory
     */
    private $durationFactory;
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
        $this->areaFactory = new AreaFactory();
        $this->roomFactory = new RoomFactory();
        $this->durationFactory = new DurationFactory();
        $periodicityFactory = new PeriodicityFactory();
        $this->entryFactory = new EntryFactory($periodicityFactory);
    }

    public function testCreateNew(): void
    {
        $durationModel = $this->durationFactory->createNew();

        $this->assertInstanceOf(DurationModel::class, $durationModel);
    }

    /**
     * @dataProvider getDataForCreateNewFromEntry
     */
    public function testCreateNewFromEntry(
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

        $entry = $this->entryFactory->initEntryForNew($area, $room, $year, $month, $day, $hour, $minute);
        $durationModel = $this->durationFactory->createByEntry($entry);

        $this->assertInstanceOf(DurationModel::class, $durationModel);
    }

    /**
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     */
    public function testCreateByDates(): void
    {
        $format = 'Y-m-d H:i';
        $start = DateTime::createFromFormat($format, '2019-10-22 10:00');
        $end = DateTime::createFromFormat($format, '2019-10-22 10:30');

        $durationModel = $this->durationFactory->createFromDates($start, $end);
        $this->assertInstanceOf(DurationModel::class, $durationModel);
    }

    /**
     * @dataProvider getDataForMinutes
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @param float              $result
     */
    public function testUnitInMinutes(DateTimeInterface $start, DateTimeInterface $end, float $result): void
    {
        $durationModel = $this->durationFactory->createFromDates($start, $end);
        $this->assertInstanceOf(DurationModel::class, $durationModel);

        $this->assertSame(DurationModel::UNIT_TIME_MINUTES, $durationModel->getUnit());
        $this->assertSame($result, $durationModel->getTime());
    }

    /**
     * @dataProvider getDataForHours
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @param float              $result
     */
    public function testUnitInHours(DateTimeInterface $start, DateTimeInterface $end, float $result): void
    {
        $durationModel = $this->durationFactory->createFromDates($start, $end);
        $this->assertInstanceOf(DurationModel::class, $durationModel);

        $this->assertSame(DurationModel::UNIT_TIME_HOURS, $durationModel->getUnit());
        $this->assertSame($result, $durationModel->getTime());
    }

    /**
     * @dataProvider getDataForDays
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @param float              $result
     */
    public function testUnitInDays(DateTimeInterface $start, DateTimeInterface $end, float $result): void
    {
        $durationModel = $this->durationFactory->createFromDates($start, $end);
        $this->assertInstanceOf(DurationModel::class, $durationModel);

        $this->assertSame(DurationModel::UNIT_TIME_DAYS, $durationModel->getUnit());
        $this->assertSame($result, $durationModel->getTime());
    }

    /**
     * @dataProvider getDataForWeeks
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @param float              $result
     */
    public function testUnitInWeeks(DateTimeInterface $start, DateTimeInterface $end, float $result): void
    {
        $durationModel = $this->durationFactory->createFromDates($start, $end);
        $this->assertInstanceOf(DurationModel::class, $durationModel);

        $this->assertSame(DurationModel::UNIT_TIME_WEEKS, $durationModel->getUnit());
        $this->assertSame($result, $durationModel->getTime());
    }

    /**
     * @return float[][]|\DateTime[][]|bool[][]
     */
    public function getDataForMinutes(): array
    {
        $format = 'Y-m-d H:i';

        return [
            [
                'start' => DateTime::createFromFormat($format, '2019-10-22 10:00'),
                'end' => DateTime::createFromFormat($format, '2019-10-22 10:30'),
                'result' => 30.0,
            ],
            [
                'start' => DateTime::createFromFormat($format, '2019-10-22 8:17'),
                'end' => DateTime::createFromFormat($format, '2019-10-22 8:52'),
                'result' => 35.0,
            ],
        ];
    }

    /**
     * @return float[][]|\DateTime[][]|bool[][]
     */
    public function getDataForHours(): array
    {
        $format = 'Y-m-d H:i';

        return [
            [
                'start' => DateTime::createFromFormat($format, '2019-10-22 9:00'),
                'end' => DateTime::createFromFormat($format, '2019-10-22 12:30'),
                'result' => 3.5,
            ],
            [
                'start' => DateTime::createFromFormat($format, '2017-10-01 14:00'),
                'end' => DateTime::createFromFormat($format, '2017-10-01 16:45'),
                'result' => 2.75,
            ],
            [
                'start' => DateTime::createFromFormat($format, '2019-10-22 15:17'),
                'end' => DateTime::createFromFormat($format, '2019-10-22 18:52'),
                'result' => 3.58,
            ],
        ];
    }

    /**
     * @return float[][]|\DateTime[][]|bool[][]
     */
    public function getDataForDays(): array
    {
        $format = 'Y-m-d H:i';

        return [
            [
                'start' => DateTime::createFromFormat($format, '2019-10-20 10:00'),
                'end' => DateTime::createFromFormat($format, '2019-10-25 10:30'),
                'result' => 5.0,
            ],
            [
                'start' => DateTime::createFromFormat($format, '2019-10-11 8:17'),
                'end' => DateTime::createFromFormat($format, '2019-10-12 8:52'),
                'result' => 1.0,
            ],
        ];
    }

    /**
     * @return float[][]|\DateTime[][]|bool[][]
     */
    public function getDataForWeeks(): array
    {
        $format = 'Y-m-d H:i';

        return [
            [
                'start' => DateTime::createFromFormat($format, '2019-07-01 10:00'),
                'end' => DateTime::createFromFormat($format, '2019-07-30 10:30'),
                'result' => 4.0,
            ],
            [
                'start' => DateTime::createFromFormat($format, '2019-11-11 8:17'),
                'end' => DateTime::createFromFormat($format, '2019-12-20 8:52'),
                'result' => 5.0,
            ],
        ];
    }

    public function getDataForCreateNewFromEntry(): array
    {
        return [
            [2019, 8, 19, 10, 0],
            [2019, 28, 2, 9, 0],
            [2019, 31, 12, 14, 30],
            [2019, 20, 30, 16, 20],
        ];
    }
}
