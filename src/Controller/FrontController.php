<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\Entry;
use App\Factory\CarbonFactory;
use App\Model\Day;
use App\Model\Hour;
use App\Model\Month;
use App\Model\Week;
use App\Repository\AreaRepository;
use App\Repository\EntryRepository;
use App\Repository\RoomRepository;
use App\Service\AreaService;
use App\Service\CalendarDataManager;
use App\Service\MonthHelperDataDisplay;
use App\Service\Settingservice;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FrontController
 * @package App\Controller
 * @Route("/front")
 */
class FrontController extends AbstractController
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var MonthHelperDataDisplay
     */
    private $calendarDisplay;
    /**
     * @var CalendarDataManager
     */
    private $calendarDataManager;
    /**
     * @var Settingservice
     */
    private $settingservice;
    /**
     * @var AreaService
     */
    private $areaService;
    /**
     * @var MonthHelperDataDisplay
     */
    private $monthHelperDataDisplay;

    public function __construct(
        Settingservice $settingservice,
        MonthHelperDataDisplay $monthHelperDataDisplay,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        EntryRepository $entryRepository,
        CalendarDataManager $calendarDataManager,
        AreaService $areaService
    ) {
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->entryRepository = $entryRepository;
        $this->calendarDataManager = $calendarDataManager;
        $this->settingservice = $settingservice;
        $this->areaService = $areaService;
        $this->monthHelperDataDisplay = $monthHelperDataDisplay;
    }

    /**
     * @Route("/", name="grr_front_home", methods={"GET"})
     */
    public function index(): Response
    {
        $today = CarbonFactory::getToday();

        $esquare = $this->settingservice->getDefaultArea();

        return $this->redirectToRoute(
            'grr_front_month',
            ['area' => $esquare->getId(), 'year' => $today->year, 'month' => $today->month]
        );
    }

    /**
     * @Route("/monthview/area/{area}/year/{year}/month/{month}/room/{room}", name="grr_front_month", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * //prend room = 1 meme si pas selectionne
     * Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=false})
     */
    public function month(Area $area, int $year, int $month, int $room = null): Response
    {
        $roomObject = null;

        if ($room) {
            $roomObject = $this->roomRepository->find($room);
        }

        $monthModel = Month::createJf($year, $month);
        $this->calendarDataManager->bindMonth($monthModel, $area, $roomObject);

        $monthData = $this->monthHelperDataDisplay->generateHtmlMonth($monthModel);

        return $this->render(
            'front/month.html.twig',
            [
                'firstDay' => $monthModel->firstOfMonth(),
                'area' => $area,
                'room' => $roomObject,
                'data' => $monthData,
            ]
        );
    }

    /**
     * @Route("/weekview/area/{area}/year/{year}/month/{month}/week/{week}/room/{room}", name="grr_front_week", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=true})
     */
    public function week(Area $area, int $year, int $month, int $week, int $room = null): Response
    {
        $roomObject = null;

        if ($room) {
            //todo if room selected
            $roomObject = $this->roomRepository->find($room);
        }

        $weekModel = Week::createWithLocal($year, $week);
        $data = $this->calendarDataManager->bindWeek($weekModel, $area);

        return $this->render(
            'front/week.html.twig',
            [
                'week' => $weekModel,
                'area' => $area,//pour lien add entry
                'data' => $data,
            ]
        );
    }

    /**
     * @Route("/dayview/area/{area}/year/{year}/month/{month}/day/{day}/room/{room}", name="grr_front_day", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=true})
     */
    public function day(Area $area, int $year, int $month, int $day, int $room = null): Response
    {
        $daySelected = CarbonFactory::createImmutable($year, $month, $day);

        $rooms = $this->roomRepository->findByArea($area);

        if ($room) {
            //todo if room selected
            $rooms = [$this->roomRepository->find($room)];
        }

        $start = Carbon::today()->setTime(16, 0);
        $end = Carbon::today()->setTime(17, 20);
        $diff = $start->diffInSeconds($end);//80 minutes

        $resolution = $area->getResolutionArea();//30 minutes
        $cellules = (integer)($diff / $resolution);//2.6 arrondit a 2

        $dayModel = new Day($daySelected);

        $hoursPeriod = $this->areaService->getHoursPeriod($area, $daySelected);
        $last = $hoursPeriod->last();
        $hoursPeriod->first();

        $data = $this->calendarDataManager->bindDay($daySelected, $area);

        $colonne = $ligne = 0;
        $hours = $this->getHours($hoursPeriod);

        foreach ($data as $roomModel) {
            $entries = $roomModel->getEntries();
            foreach ($entries as $entry) {

                $cellules = $this->getCountCellules($entry, $resolution);
                $entryStart = $entry->getStartTime();
                $entryEnd = $entry->getEndTime();
                $locations = [];

                foreach ($hours as $hour) {
                    $begin = $hour->getBegin();
                    $end = $hour->getEnd();
                    if ($this->getEntry($entry, $begin, $end)) {
                        $locations[] = $hour;
                    }
                }
                $entry->setLocations($locations);
                $entry->setCellules($cellules);
                dump($locations);
            }
        }



        return $this->render(
            'front/day.html.twig',
            [
                'day' => $dayModel,
                'data' => $data,
                'area' => $area,
                'rooms' => $rooms,
                'hours' => $hours,
            ]
        );
    }

    protected function getEntry(Entry $entry, \DateTimeInterface $begin, \DateTimeInterface $end): bool
    {
        if ($begin->format('H:i') >= $entry->getStartTime()->format('H:i') &&
            $begin->format('H:i') <= $entry->getEndTime()->format('H:i')) {

            //  dump($begin->format('H:i'), '>=', $entry->getStartTime()->format('H:i'));
            //  dump($end->format('H:i'), '<=', $entry->getEndTime()->format('H:i'));

            return true;
        }

        return false;
    }

    protected function getCountCellules(Entry $entry, int $resolution)
    {
        $start = Carbon::instance($entry->getStartTime());
        $end = Carbon::instance($entry->getEndTime());
        $diff = $start->diffInSeconds($end);//80 minutes

        return (integer)(round($diff / $resolution));
    }

    /**
     * @param CarbonPeriod $hoursPeriod
     * @return Hour[]
     */
    protected function getHours(CarbonPeriod $hoursPeriod)
    {
        $hours = [];
        $hoursPeriod->rewind();
        $last = $hoursPeriod->last();
        $hoursPeriod->rewind();

        while ($hoursPeriod->current()->lessThan($last)) {

            $begin = $hoursPeriod->current();
            $hoursPeriod->next();
            $end = $hoursPeriod->current();

            $hour = new Hour();
            $hour->setBegin($begin);
            $hour->setEnd($end);
            $hours[] = $hour;
        }

        return $hours;
    }

}
