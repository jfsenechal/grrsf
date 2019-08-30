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
use App\I18n\LocalHelper;
use App\Model\TimeSlot;
use App\Provider\TimeSlotsProvider;
use App\Service\EntryLocationService;
use App\Tests\Repository\BaseRepository;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class EntryLocationServiceTest extends BaseRepository
{
    public function testSetLocations()
    {
        //  $this->loadFixtures();

        $hourBegin = 8;
        $hourEnd = 19;
        $duration = 1800;

        $today = new \DateTime('now');
        $today->setTime(13, 0);

        $end = clone $today;
        $end->setTime(15, 30);

        $area = $this->initArea($hourBegin, $hourEnd, $duration);
        $room = new Room($area);

        $entry = new Entry();
        $entry->setStartTime($today);
        $entry->setEndTime($end);
        $entry->setRoom($room);

        $timeSlotProvider = $this->initTimeSlotProvider();
        $timesSlot = $timeSlotProvider->getTimeSlotsModelByArea($area);

        $entryService = new EntryLocationService($timeSlotProvider);
        $entryService->setLocations($entry, $timesSlot);
        /**
         * @var TimeSlot[] $locations
         */
        $locations = $entry->getLocations();

        /**
         * = heure de debut
         */
        $day = Carbon::today();
        $day->setTime(13,0);

        self::assertCount(6, $locations);

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
        $requestStack = $this->createMock(RequestStack::class);
        $localHelper = new LocalHelper($parameterBag, $requestStack);
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

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'entry_with_periodicity.yaml',
                $this->pathFixtures.'periodicity_day.yaml',
            ];

        $this->loader->load($files);
    }
}