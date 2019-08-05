<?php

namespace App\Controller;

use App\Entity\Area;
use App\Factory\CarbonFactory;
use App\Factory\MenuGenerator;
use App\Model\Day;
use App\Model\Hour;
use App\Model\Month;
use App\Model\Week;
use App\Repository\AreaRepository;
use App\Repository\EntryRepository;
use App\Repository\RoomRepository;
use App\Service\CalendarDataDisplay;
use App\Service\CalendarDataManager;
use App\Service\CalendarNavigationDisplay;
use App\Service\LocalHelper;
use Carbon\Carbon;
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
     * @var CalendarDataDisplay
     */
    private $calendarDisplay;
    /**
     * @var CalendarNavigationDisplay
     */
    private $calendarNavigationDisplay;
    /**
     * @var LocalHelper
     */
    private $localHelper;
    /**
     * @var MenuGenerator
     */
    private $menuGenerator;
    /**
     * @var CalendarDataManager
     */
    private $calendarDataManager;

    public function __construct(
        CalendarDataDisplay $calendarDisplay,
        MenuGenerator $menuGenerator,
        CalendarNavigationDisplay $calendarNavigationDisplay,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        EntryRepository $entryRepository,
        CalendarDataManager $calendarDataManager
    ) {
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->entryRepository = $entryRepository;
        $this->calendarDisplay = $calendarDisplay;
        $this->calendarNavigationDisplay = $calendarNavigationDisplay;
        $this->menuGenerator = $menuGenerator;
        $this->calendarDataManager = $calendarDataManager;
    }

    /**
     * @Route("/test", name="grr_front_test", methods={"GET"})
     */
    public function test(): Response
    {
        return $this->render(
            'front/test.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/", name="grr_front_home", methods={"GET"})
     */
    public function index(): Response
    {
        $today = CarbonFactory::getToday();

        //todo get area by default
        $esquare = $this->areaRepository->find(1);

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

        $entries = $this->entryRepository->findForMonth($monthModel, $area, $roomObject);

        $this->calendarDataManager->bindMonth($monthModel, $entries);

        $monthData = $this->calendarDisplay->generateMonth($monthModel);

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
        $rooms = $this->roomRepository->findByArea($area);
        $roomObject = null;

        if ($room) {
            $roomObject = $this->roomRepository->find($room);
        }

        $weekModel = Week::createWithLocal($year, $week);

        $entries = $this->entryRepository->findForWeek($weekModel, $area, $roomObject);
        $this->calendarDataManager->bindWeek($weekModel, $entries);

        $firstDay = $weekModel->getFirstDay();

        $this->calendarDisplay->generateWeek($weekModel);

        return $this->render(
            'front/week.html.twig',
            [
                'firstDay' => $firstDay,//utilise par form select
                'week' => $weekModel,
                'rooms' => $rooms,
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

        $entries = $this->entryRepository->findAll();
        $dayModel = new Day($daySelected);
        $dayModel->addEntries($entries);

        $heureDebut = $area->getMorningstartsArea();
        $heureFin = $area->getEveningendsArea();
        $resolution = $area->getResolutionArea();

        $debut = Carbon::create($year, $month, $day, $heureDebut, 0);
        $fin = Carbon::create($year, $month, $day, $heureFin, 0, 0);//$resolution bug

        $heures = Carbon::parse($debut)->secondsUntil($fin, $resolution);

        $hours = [];
        $hour = new Hour();
        $i = 0;
        foreach ($heures as $heure) {
            //premier passage
            if ($i == 0) {
                $hour->setBegin($heure);
            } else {
                $hour->setEnd($heure);
                $hours[] = $hour;
                $hour = new Hour();
                $hour->setBegin($heure);
            }
            $i = 1;
        }

        return $this->render(
            'front/day.html.twig',
            [
                'firstDay' => $daySelected,
                'day' => $dayModel,
                'rooms' => $rooms,
                'hours' => $hours,
            ]
        );
    }


}
