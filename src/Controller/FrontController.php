<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\Room;
use App\Form\AreaMenuSelectType;
use App\Navigation\MenuSelect;
use App\Repository\AreaRepository;
use App\Repository\EntryRepository;
use App\Repository\RoomRepository;
use App\Service\Calendar;
use App\Service\CalendarDataManager;
use App\Service\CalendarDisplay;
use App\Service\CalendarNavigationDisplay;
use App\Service\WeekService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @var Calendar
     */
    private $calendar;
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
     * @var WeekService
     */
    private $weekService;

    public function __construct(
        Calendar $calendar,
        CalendarDisplay $calendarDisplay,
        CalendarNavigationDisplay $calendarNavigationDisplay,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        EntryRepository $entryRepository,
        WeekService $weekService
    ) {
        $this->calendar = $calendar;
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->entryRepository = $entryRepository;
        $this->calendarDisplay = $calendarDisplay;
        $this->calendarNavigationDisplay = $calendarNavigationDisplay;
        $this->weekService = $weekService;
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
     * Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=false})
     */
    public function month(Area $area, int $year, int $month, int $room = null): Response
    {
        $startDate = \DateTime::createFromFormat('Y-m-d', $year.'-'.$month.'-01');
        $startDateImmutable = \DateTimeImmutable::createFromFormat('Y-m-d', $year.'-'.$month.'-01');

        $this->calendar->createCalendarFromDate($startDateImmutable);

        $areas = $this->areaRepository->findAll();
        $entries = $this->entryRepository->findAll();

        $form = $this->generateMenuSelect($area, $room);

        $calendarDataManager = new CalendarDataManager();
        $calendarDataManager->setEntries($entries);

        $dataMonth = $this->calendarDisplay->oneMonth($startDateImmutable, $calendarDataManager);

        $navigation = $this->calendarNavigationDisplay->create($area, $month);

        return $this->render(
            'front/month.html.twig',
            [
                'first' => $this->calendar->getFirstDay(),
                'current' => $startDate,
                'areas' => $areas,
                'area' => $area,
                'entries' => $entries,
                'data' => $dataMonth,
                'navigation' => $navigation,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/day/area/{area}/year/{year}/month/{month}/day/{day}/room/{room}", name="grr_front_day", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * @Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=true})
     */
    public function day(Area $area, int $year, int $month, int $day, Room $room = null): Response
    {
        return $this->render(
            'front/day.html.twig',
            []
        );
    }

    /**
     * @Route("/week/area/{area}/{year}/{week}/room/{room}", name="grr_front_week", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * @Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=true})
     */
    public function week(Area $area, int $year, int $week, Room $room = null): Response
    {
        $data = '';

        $startDate = new \DateTimeImmutable();

        $this->calendar->createCalendarFromDate($startDate);

        $areas = $this->areaRepository->findAll();
        $entries = $this->entryRepository->findAll();

        $rooms = $this->roomRepository->findByArea($area);

        $form = $this->generateMenuSelect($area);
        $navigation = $this->calendarNavigationDisplay->create();

        $days = $this->weekService->getDays($year, $week);

        return $this->render(
            'front/week.html.twig',
            [
                'data' => $data,
                'form' => $form->createView(),
                'current' => $startDate,
                'navigation' => $navigation,
                'days' => $days,
                'rooms' => $rooms,
            ]
        );
    }

    private function generateMenuSelect(Area $area, int $roomId = null)
    {
        $menuSelect = new MenuSelect();
        $menuSelect->setArea($area);

        if ($roomId) {
            $room = $this->roomRepository->find($roomId);
            $menuSelect->setRoom($room);
        }

        return $this->createForm(AreaMenuSelectType::class, $menuSelect);
    }

    /**
     * @Route("/ajax/getrooms", name="grr_ajax_getrooms")
     */
    public function ajaxRequestGetRooms(Request $request)
    {
        $areaId = $request->get('id');
        $area = $this->areaRepository->find($areaId);
        $rooms = $this->roomRepository->findByArea($area);

        return $this->render('front/_rooms_options.html.twig', ['rooms' => $rooms]);
    }

}
