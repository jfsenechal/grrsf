<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 20/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Service;


use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use App\Factory\CarbonFactory;
use App\Helper\LocalHelper;
use App\Model\TimeSlot;
use App\Provider\TimeSlotsProvider;
use App\Service\EntryService;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EntryServiceTest extends WebTestCase
{
    public function testSetLocations()
    {
        $hourBegin = 8;
        $hourEnd = 19;
        $duration = 1800;

        $area = $this->initArea($hourBegin, $hourEnd, $duration);
        $room = new Room($area);

        $entry = new Entry();
        $entry->setStartTime(\DateTime::createFromFormat('Y-m-d H:i', '2019-08-20 13:00'));
        $entry->setEndTime(\DateTime::createFromFormat('Y-m-d H:i', '2019-08-20 15:30'));
        $entry->setRoom($room);

        $timeSlotProvider = $this->initTimeSlotProvider();
        $timesSlot = $timeSlotProvider->getTimeSlotsModelByAreaAndDay($area);

        $entryService = new EntryService($timeSlotProvider);
        $entryService->setLocations($entry, $timesSlot);
        /**
         * @var TimeSlot[] $locations
         */
        $locations = $entry->getLocations();

        /**
         * = heure de debut
         */
        $day = Carbon::create(2019, 8, 20, 13, 0);

        foreach ($locations as $location) {
            $begin = $location->getBegin();
            $end = $location->getEnd();
            self::assertSame("$begin->hour:$begin->minute", "$day->hour:$day->minute");
            $day->addSeconds($duration);
            self::assertSame("$end->hour:$end->minute", "$day->hour:$day->minute");
        }
    }

    public function te2stIsEntryInTimeSlot()
    {
        /*  CarbonPeriod $entryTimeSlots,
          \DateTimeInterface $startTimeSlot,
          \DateTimeInterface $endTimeSlot*/
    }


    protected function initTimeSlotProvider(): TimeSlotsProvider
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