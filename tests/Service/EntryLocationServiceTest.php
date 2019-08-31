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
use Carbon\CarbonPeriod;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class EntryLocationServiceTest extends BaseRepository
{
    /**
     * @dataProvider getData
     * @throws \Exception
     */
    public function testSetLocations(
        int $entryHourBegin,
        int $entryMinuteBegin,
        int $entryHourEnd,
        int $entryMinuteEnd,
        int $countLocations
    ) {
        //  $this->loadFixtures();
        $duration = 1800;
        $today = new \DateTime('now');
        $today->setTime($entryHourBegin, $entryMinuteBegin);

        $daySelected = Carbon::instance($today);

        $end = clone $today;
        $end->setTime($entryHourEnd, $entryMinuteEnd);

        $area = $this->initArea(8, 19, $duration);
        $room = new Room($area);

        $entry = new Entry();
        $entry->setStartTime($today);
        $entry->setEndTime($end);
        $entry->setRoom($room);

        $timeSlotProvider = $this->initTimeSlotProvider();
        $timesSlot = $timeSlotProvider->getTimeSlotsModelByAreaAndDaySelected($area, $daySelected);

        $entryService = new EntryLocationService($timeSlotProvider);
        $locations = $entryService->getLocations($entry, $timesSlot);

        /**
         * = heure de debut
         */
        $day = Carbon::today();
        $day->setTime($entryHourBegin, 0);

        self::assertCount($countLocations, $locations);

        $startEntry = Carbon::instance($entry->getStartTime());
        $endEntry = Carbon::instance($entry->getEndTime());

        foreach ($locations as $location) {
            $begin = $location->getBegin();
            $end = $location->getEnd();

            self::assertTrue($startEntry->greaterThanOrEqualTo($begin) || $startEntry->lessThanOrEqualTo($end));
            self::assertTrue($begin->lessThanOrEqualTo($endEntry));
        }
    }

    public function getData()
    {
        return [
            [
                'entryHourBegin' => 13,
                'entryMinuteBegin' => 0,
                'entryHourEnd' => 15,
                'entryMinuteEnd' => 30,
                'countLocations' => 5,
            ],
            [
                'entryHourBegin' => 8,
                'entryMinuteBegin' => 10,
                'entryHourEnd' => 12,
                'entryMinuteEnd' => 55,
                'countLocations' => 10,
            ],
            [
                'entryHourBegin' => 9,
                'entryMinuteBegin' => 30,
                'entryHourEnd' => 10,
                'entryMinuteEnd' => 0,
                'countLocations' => 1,
            ],
        ];
    }

    /**
     * @dataProvider getDataMultipleDays
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     * @param array $countLocations
     */
    public function testMultipleDaysSetLocations(
        \DateTime $dateStart,
        \DateTime $dateEnd,
        array $countLocations
    ) {
        $duration = 1800;

        $area = $this->initArea(8, 19, $duration);
        $room = new Room($area);

     //   echo $dateStart->format('Y-m-d H:i').' '.$dateEnd->format('Y-m-d H:i')."\n \n";

        $entry = new Entry();
        $entry->setStartTime($dateStart);
        $entry->setEndTime($dateEnd);
        $entry->setRoom($room);

        $timeSlotProvider = $this->initTimeSlotProvider();

        $days = CarbonPeriod::between($dateStart, $dateEnd);
        $i = 0;
        foreach ($days as $daySelected) {
            $timesSlot = $timeSlotProvider->getTimeSlotsModelByAreaAndDaySelected($area, $daySelected);

            $entryService = new EntryLocationService($timeSlotProvider);
            $locations = $entryService->getLocations($entry, $timesSlot);

            /**
             * = heure de debut
             */
            $day = Carbon::today();
            //  $day->setTime($entryHourBegin, 0);
         //   var_dump($i);
            self::assertCount($countLocations[$i], $locations);

            $startEntry = Carbon::instance($entry->getStartTime());
            $endEntry = Carbon::instance($entry->getEndTime());

            foreach ($locations as $location) {
                $begin = $location->getBegin();
                $end = $location->getEnd();

                //  self::assertTrue($startEntry->greaterThanOrEqualTo($begin) || $startEntry->lessThanOrEqualTo($end));
                //  self::assertTrue($begin->lessThanOrEqualTo($endEntry));
            }
            $i++;
        }
    }

    public function getDataMultipleDays()
    {
        return [
            [
                'dayStart' => \DateTime::createFromFormat('Y-m-d H:i', '2019-08-05 09:00'),
                'dayEnd' => \DateTime::createFromFormat('Y-m-d H:i', '2019-08-08 09:00'),
                'countsLocations' => [20, 22, 22, 2],
            ],
        ];
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