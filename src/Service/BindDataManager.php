<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 21/03/19
 * Time: 11:35.
 */

namespace App\Service;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use App\Factory\CarbonFactory;
use App\Model\Day;
use App\Model\TimeSlot;
use App\Model\Month;
use App\Model\RoomModel;
use App\Model\Week;
use App\Repository\EntryRepository;
use Carbon\CarbonInterface;

class BindDataManager
{
    /**
     * @var Entry[]
     */
    protected $entries;
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var EntryService
     */
    private $entryService;

    public function __construct(EntryRepository $entryRepository, EntryService $entryService)
    {
        $this->entries = [];
        $this->entryRepository = $entryRepository;
        $this->entryService = $entryService;
    }

    /**
     * Parcours tous les jours du mois
     * Crée une instance Day et set les entrées.
     *
     * @param Month   $param
     * @param Entry[] $entries
     *
     * @throws \Exception
     */
    public function bindMonth(Month $monthModel, Area $area, Room $room = null)
    {
        $entries = $this->entryRepository->findForMonth($monthModel, $area, $room);
        $this->entries = $entries;

        foreach ($monthModel->getCalendarDays() as $date) {
            $day = new Day($date);
            $events = $this->extractByDate($day);
            $day->addEntries($events);
            $monthModel->addDataDay($day);
        }
    }

    /**
     * @param Week $week
     *
     * @return RoomModel[]
     *
     * @throws \Exception
     */
    public function bindWeek(Week $weekModel, Area $area)
    {
        $days = $weekModel->getCalendarDays();
        $year = $weekModel->year;
        $month = $weekModel->month;
        $data = [];

        foreach ($area->getRooms() as $room) {
            $roomModel = new RoomModel($room);
            foreach ($days as $dayCalendar) {
                $daySelected = CarbonFactory::createImmutable($year, $month, $dayCalendar->day);
                $dataDay = new Day($daySelected);
                $entries = $this->entryRepository->findForWeek($dayCalendar, $room);
                $dataDay->addEntries($entries);
                $roomModel->addDataDay($dataDay);
            }
            $data[] = $roomModel;
        }

        return $data;
    }

    /**
     * Genere des RoomModel avec les entrées pour chaque Room
     * Puis pour chaque entrées en calcul le nbre de cellules qu'elle occupe
     * et sa localisation.
     *
     * @param CarbonInterface $day
     * @param Area            $area
     * @param TimeSlot[]      $hoursModel
     *
     * @return RoomModel[]
     */
    public function bindDay(CarbonInterface $day, Area $area, array $hoursModel)
    {
        $roomsModel = [];
        foreach ($area->getRooms() as $roomObject) {
            $roomModel = new RoomModel($roomObject);
            $entries = $this->entryRepository->findForDay($day, $roomObject);
            $roomModel->setEntries($entries);
            $roomsModel[] = $roomModel;
        }

        foreach ($roomsModel as $roomModel) {
            /**
             * @var Entry[]
             */
            $entries = $roomModel->getEntries();
            foreach ($entries as $entry) {
                $this->entryService->setLocations($entry, $hoursModel);
                $count = count($entry->getLocations());
                $entry->setCellules($count);
                //$this->entryService->setCountCells($entry, $area);
            }
        }

        return $roomsModel;
    }

    public function extractByDate(\DateTimeInterface $dateTime)
    {
        $data = [];
        foreach ($this->entries as $entry) {
            if ($entry->getStartTime()->format('Y-m-d') === $dateTime->format('Y-m-d')) {
                $data[] = $entry;
            }
        }

        return $data;
    }
}
