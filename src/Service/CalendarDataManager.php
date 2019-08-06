<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 21/03/19
 * Time: 11:35
 */

namespace App\Service;

use App\Entity\Area;
use App\Entity\Entry;
use App\Factory\CarbonFactory;
use App\Model\Day;
use App\Model\Month;
use App\Model\RoomModel;
use App\Model\Week;
use App\Repository\EntryRepository;

class CalendarDataManager
{
    /**
     * @var Entry[] $entries
     */
    protected $entries;
    /**
     * @var EntryRepository
     */
    private $entryRepository;

    public function __construct(EntryRepository $entryRepository)
    {
        $this->entries = [];
        $this->entryRepository = $entryRepository;
    }

    /**
     * Parcours tous les jours du mois
     * Crée une instance Day et set les entrées
     *
     * @param Month $param
     * @param Entry[] $entries
     * @throws \Exception
     */
    public function bindMonth(Month $month, array $entries)
    {
        $this->entries = $entries;
        foreach ($month->getCalendarDays() as $date) {
            $day = new Day($date);
            $events = $this->extractByDate($day);
            $day->addEntries($events);
            $month->addDataDay($day);
        }
    }

    /**
     * @param Week $week
     * @return RoomModel[]
     * @throws \Exception
     */
    public function bindWeek(Week $weekModel, Area $area)
    {
        $days = $weekModel->getCalendarDays();
        $year = $weekModel->year;
        $month = $weekModel->month;
        $data = [];

        foreach ($area->getRooms() as $room2) {
            $roomModel = new RoomModel($room2);
            foreach ($days as $dayCalendar) {
                $daySelected = CarbonFactory::createImmutable($year, $month, $dayCalendar->day);
                $dataDay = new Day($daySelected);
                $entries = $this->entryRepository->findForWeek($dayCalendar, $room2);
                $dataDay->addEntries($entries);
                $roomModel->addDataDay($dataDay);
            }
            $data[] = $roomModel;
        }

        return $data;
    }

    public function extractByDate(\DateTimeInterface $dateTime)
    {
        $data = [];
        foreach ($this->entries as $entry) {
            if ($entry->getStartTime()->format('Y-m-d') == $dateTime->format('Y-m-d')) {
                $data[] = $entry;
            }
        }

        return $data;
    }
}