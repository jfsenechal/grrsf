<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\Room;
use App\Form\AreaMenuSelectType;
use App\Model\Day;
use App\Model\Hour;
use App\Model\Month;
use App\Model\Week;
use App\Navigation\MenuSelect;
use App\Repository\AreaRepository;
use App\Repository\EntryRepository;
use App\Repository\RoomRepository;
use App\Service\CalendarDataManager;
use App\Service\CalendarDisplay;
use App\Service\CalendarNavigationDisplay;
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
     * @var Month
     */
    private $month;
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
     * @var CalendarDisplay
     */
    private $calendarDisplay;
    /**
     * @var CalendarNavigationDisplay
     */
    private $calendarNavigationDisplay;
    /**
     * @var Week
     */
    private $week;

    public function __construct(
        Month $month,
        CalendarDisplay $calendarDisplay,
        CalendarNavigationDisplay $calendarNavigationDisplay,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        EntryRepository $entryRepository,
        Week $week
    ) {
        $this->month = $month;
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->entryRepository = $entryRepository;
        $this->calendarDisplay = $calendarDisplay;
        $this->calendarNavigationDisplay = $calendarNavigationDisplay;
        $this->week = $week;
    }

    /**
     * @Route("/", name="grr_front_home", methods={"GET"})
     */
    public function index(): Response
    {
        $month = date('n');
        $year = date('Y');
        $esquare = $this->areaRepository->find(1);

        return $this->redirectToRoute(
            'grr_front_month',
            ['area' => $esquare->getId(), 'year' => $year, 'month' => $month]
        );


    }

    /**
     * @Route("/month/area/{area}/year/{year}/month/{month}/room/{room}", name="grr_front_month", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * //prend room = 1 meme si pas selectionne
     * Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=false})
     */
    public function month(Area $area, int $year, int $month, int $room = null): Response
    {
        $monthModel = $this->month->create($year, $month);

        $entries = $this->entryRepository->findAll();
        $roomObject = null;

        if ($room) {
            $roomObject = $this->roomRepository->find($room);
        }

        $form = $this->generateMenuSelect($area, $roomObject);

        $calendarDataManager = new CalendarDataManager();
        $calendarDataManager->setEntries($entries);

        $data = $this->calendarDisplay->oneMonth($monthModel, $calendarDataManager);

        $navigation = $this->calendarNavigationDisplay->create($monthModel);

        return $this->render(
            'front/month.html.twig',
            [
                'firstDay' => $this->month->getFirstDay(),
                'area' => $area,
                'room' => $roomObject,
                'data' => $data,
                'navigation' => $navigation,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/week/area/{area}/year/{year}/month/{month}/week/{week}/room/{room}", name="grr_front_week", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=true})
     */
    public function week(Area $area, int $year, int $month, int $week, int $room = null): Response
    {
        $entries = $this->entryRepository->findAll();

        $rooms = $this->roomRepository->findByArea($area);

        $form = $this->generateMenuSelect($area);

        $monthModel = $this->month->create($year, $month);

        $navigation = $this->calendarNavigationDisplay->create($monthModel);

        $weekModel = $this->week->create($year, $week);
        $firstDay = $weekModel->getFirstDay();

        $calendarDataManager = new CalendarDataManager();
        $calendarDataManager->setEntries($entries);

        return $this->render(
            'front/week.html.twig',
            [
                'form' => $form->createView(),
                'firstDay' => $firstDay,//utilise par form select
                'navigation' => $navigation,
                'week' => $weekModel,
                'rooms' => $rooms,
            ]
        );
    }

    /**
     * @Route("/day/area/{area}/year/{year}/month/{month}/day/{day}/room/{room}", name="grr_front_day", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=true})
     */
    public function day(Area $area, int $year, int $month, int $day, int $room = null): Response
    {
        $daySelected = \DateTimeImmutable::createFromFormat('Y-m-d', $year.'-'.$month.'-'.$day);

        $entries = $this->entryRepository->findAll();

        $rooms = $this->roomRepository->findByArea($area);

        $form = $this->generateMenuSelect($area);

        $monthModel = $this->month->create($year, $month);

        $navigation = $this->calendarNavigationDisplay->create($monthModel);

        $dayModel = new Day($daySelected);
        $dayModel->setEntries($entries);

        $heureDebut = $area->getMorningstartsArea();
        $heureFin = $area->getEveningendsArea();
        $resolution = $area->getResolutionArea();

        $debut = \DateTime::createFromFormat('Y-m-d', $year.'-'.$month.'-'.$day);
        $fin = clone($debut);

        $debut->setTime($heureDebut, 0);
        $fin->setTime($heureFin, 0,$resolution);

        $interval = new \DateInterval('PT'.$resolution.'S');
        $heures = new \DatePeriod($debut, $interval, $fin);

        dump($heures);

        $i = 0;

        $hours = [];
        $hour = new Hour();

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

        dump($hours);

        return $this->render(
            'front/day.html.twig',
            [
                'form' => $form->createView(),
                'firstDay' => $daySelected,
                'navigation' => $navigation,
                'day' => $dayModel,
                'rooms' => $rooms,
                'hours' => $hours,
            ]
        );
    }

    private function generateMenuSelect(Area $area, Room $room = null)
    {
        $menuSelect = new MenuSelect();
        $menuSelect->setArea($area);

        if ($room) {
            $menuSelect->setRoom($room);
        }

        return $this->createForm(AreaMenuSelectType::class, $menuSelect);
    }

}
