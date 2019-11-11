<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 20/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Provider;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use App\Factory\CarbonFactory;
use App\I18n\LocalHelper;
use App\Provider\TimeSlotsProvider;
use App\Tests\BaseTesting;
use Carbon\Carbon;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class TimeSlotsProviderTest extends BaseTesting
{
    /**
     * @dataProvider getData
     */
    public function testGetTimeSlotsModelByAreaAndDay(int $hourBegin, int $hourEnd, int $resolution, int $minute): void
    {
        $area = $this->initArea($hourBegin, $hourEnd, $resolution);

        $day = Carbon::today();
        $day->hour = $hourBegin;
        $day->minute = $minute;

        $timeSlotProvicer = $this->initTimeSlotProvider();
        $modelsTimeSlot = $timeSlotProvicer->getTimeSlotsModelByAreaAndDaySelected($area, $day);
        self::assertGreaterThan(0, count($modelsTimeSlot));

        foreach ($modelsTimeSlot as $modelTimeSlot) {
            self::assertSame($modelTimeSlot->getBegin()->hour, $day->hour);
            self::assertSame($modelTimeSlot->getBegin()->minute, $day->minute);
            $day->addMinutes($resolution);
            self::assertSame($modelTimeSlot->getEnd()->hour, $day->hour);
            self::assertSame($modelTimeSlot->getEnd()->minute, $day->minute);
        }
    }

    /**
     * @dataProvider getData
     */
    public function testGetTimeSlots(int $hourBegin, int $hourEnd, int $resolution, int $minute): void
    {
        $day = Carbon::today();

        $timeSlotProvicer = $this->initTimeSlotProvider();
        $timesSlot = $timeSlotProvicer->getTimeSlots($day, $hourBegin, $hourEnd, $resolution);

        $day->hour = $hourBegin;
        $day->minute = $minute;

        foreach ($timesSlot as $timeSlot) {
            self::assertSame($timeSlot->hour, $day->hour);
            self::assertSame($timeSlot->minute, $day->minute);
            $day->addMinutes($resolution);
        }
    }

    public function getTimeSlotsOfEntry(): void
    {
        $day = Carbon::today();
        $resolution = 30;

        $area = new Area();
        $area->setTimeInterval($resolution);
        $room = new Room($area);

        $entry = new Entry();
        $entry->setStartTime($day->toDateTime());
        $entry->setEndTime($day->copy()->addMinutes(180)->toDateTime());
        $entry->setRoom($room);

        $timeSlotProvicer = $this->initTimeSlotProvider();

        $timesSlot = $timeSlotProvicer->getTimeSlotsOfEntry($entry);

        foreach ($timesSlot as $timeSlot) {
            self::assertSame($timeSlot->hour, $day->hour);
            self::assertSame($timeSlot->minute, $day->minute);
            $day->addMinutes($resolution);
        }
    }

    public function getData(): array
    {
        return [
            [
                'hourBegin' => 8,
                'hourEnd' => 19,
                'resolution' => 30,
                'minute' => 0,
            ],
            [
                'hourBegin' => 10,
                'hourEnd' => 20,
                'resolution' => 15,
                'minute' => 0, //todo if != 0
            ],
        ];
    }

    protected function initTimeSlotProvider(): TimeSlotsProvider
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $security = $this->createMock(Security::class);
        $localHelper = new LocalHelper($parameterBag, $security, $requestStack);
        $carbonFactory = new CarbonFactory($localHelper);

        return new TimeSlotsProvider($carbonFactory);
    }

    protected function initArea(int $hourBegin, int $hourEnd, int $resolution): Area
    {
        $area = new Area();
        $area->setStartTime($hourBegin);
        $area->setEndTime($hourEnd);
        $area->setTimeInterval($resolution);

        return $area;
    }
}
