<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 20/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Provider;


use App\Entity\Area;
use App\Factory\CarbonFactory;
use App\Helper\LocalHelper;
use App\Provider\TimeSlotsProvider;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TimeSlotsProviderTest extends WebTestCase
{
    /**
     * @dataProvider getData
     */
    public function testGetTimeSlotsModelByAreaAndDay(int $hourBegin, int $hourEnd, int $resolution, int $minute)
    {
        $area = $this->initArea($hourBegin, $hourEnd, $resolution);

        $day = Carbon::today();
        $day->hour = $hourBegin;
        $day->minute = $minute;

        $timeSlotProvicer = $this->initCarbonFactory();
        $modelsTimeSlot = $timeSlotProvicer->getTimeSlotsModelByAreaAndDay($area, $day);

        foreach ($modelsTimeSlot as $modelTimeSlot) {
            self::assertSame($modelTimeSlot->getBegin()->hour, $day->hour);
            self::assertSame($modelTimeSlot->getBegin()->minute, $day->minute);
            $day->addSeconds($resolution);
            self::assertSame($modelTimeSlot->getEnd()->hour, $day->hour);
            self::assertSame($modelTimeSlot->getEnd()->minute, $day->minute);
        }

    }

    /**
     * @dataProvider getData
     */
    public function testGetTimeSlots(int $hourBegin, int $hourEnd, int $resolution, int $minute)
    {
        $area = $this->initArea($hourBegin, $hourEnd, $resolution);

        $day = Carbon::today();

        $timeSlotProvicer = $this->initCarbonFactory();
        $timesSlot = $timeSlotProvicer->getTimeSlots($area, $day);

        $day->hour = $hourBegin;
        $day->minute = $minute;

        foreach ($timesSlot as $timeSlot) {
            self::assertSame($timeSlot->hour, $day->hour);
            self::assertSame($timeSlot->minute, $day->minute);
            $day->addSeconds($resolution);
        }
    }


    public function getData()
    {
        return [
            [
                'hourBegin' => 8,
                'hourEnd' => 19,
                'resolution' => 1800,
                'minute' => 0,
            ],
            [
                'hourBegin' => 10,
                'hourEnd' => 20,
                'resolution' => 900,
                'minute' => 15,
            ],
        ];
    }

    protected function initCarbonFactory(): TimeSlotsProvider
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $localHelper = new LocalHelper($parameterBag);
        $carbonFactory = new CarbonFactory($localHelper);

        return new TimeSlotsProvider($carbonFactory);
    }

    protected function initArea(int $hourBegin, int $hourEnd, int $resolution): Area
    {
        $area = new Area();
        $area->setStartTime($hourBegin);
        $area->setEndTime($hourEnd);
        $area->setDurationTimeSlot($resolution);

        return $area;
    }

}