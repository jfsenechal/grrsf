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
use App\Model\Month;
use App\Model\RoomModel;
use App\Model\TimeSlot;
use App\Model\Week;
use App\Repository\EntryRepository;
use App\Repository\PeriodicityDayRepository;
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
    /**
     * @var PeriodicityDayRepository
     */
    private $periodicityDayRepository;
    /**
     * @var GeneratorEntry
     */
    private $generatorEntry;

    public function __construct(
        EntryRepository $entryRepository,
        PeriodicityDayRepository $periodicityDayRepository,
        EntryService $entryService,
        GeneratorEntry $generatorEntry
    ) {
        $this->entries = [];
        $this->entryRepository = $entryRepository;
        $this->entryService = $entryService;
        $this->periodicityDayRepository = $periodicityDayRepository;
        $this->generatorEntry = $generatorEntry;
    }

    /**
     * Parcours tous les jours du mois
     * Crée une instance Day et set les entrées.
     *
     * @param Month $param
     * @param Entry[] $entries
     *
     * @throws \Exception
     */
    public function bindMonth(Month $monthModel, Area $area, Room $room = null)
    {
        $entries = [];
        $entries[] = $this->entryRepository->findForMonth($monthModel, $area, $room);

        $periodicityDays = $this->periodicityDayRepository->findForMonth($monthModel);
        $entries [] = $this->generatorEntry->generateEntries($periodicityDays);

        $this->entries = array_merge(...$entries);

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
                $entries = [];
                $entries[] = $this->entryRepository->findForWeek($dayCalendar, $room);

                $periodicityDays = $this->periodicityDayRepository->findForWeek($dayCalendar, $room);
                $entries[] = $this->generatorEntry->generateEntries($periodicityDays);

                $dataDay->addEntries(array_merge(...$entries));
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
     * @param Area $area
     * @param TimeSlot[] $hoursModel
     *
     * @return RoomModel[]
     */
    public function bindDay(CarbonInterface $day, Area $area, array $hoursModel)
    {
        $roomsModel = [];
        foreach ($area->getRooms() as $roomObject) {
            $roomModel = new RoomModel($roomObject);
            $entries = [];
            $entries[] = $this->entryRepository->findForDay($day, $roomObject);

            $periodicityDays = $this->periodicityDayRepository->findForDay($day, $roomObject);
            $entries[] = $this->generatorEntry->generateEntries($periodicityDays);

            $roomModel->setEntries(array_merge(...$entries));
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
