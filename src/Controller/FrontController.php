<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\Room;
use App\Form\AreaMenuSelectType;
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
        $data = '';

        $firstDay = \DateTimeImmutable::createFromFormat('Y-m-d', $year.'-'.$month.'-01');

        $entries = $this->entryRepository->findAll();

        $rooms = $this->roomRepository->findByArea($area);

        $form = $this->generateMenuSelect($area);

        $monthModel = $this->month->create($year, $month);

        $navigation = $this->calendarNavigationDisplay->create($monthModel);

        $weekModel = $this->week->create($year, $week);

        $calendarDataManager = new CalendarDataManager();
        $calendarDataManager->setEntries($entries);

        $data = $this->calendarDisplay->oneWeek($weekModel, $calendarDataManager);

        return $this->render(
            'front/week.html.twig',
            [
                'data' => $data,
                'form' => $form->createView(),
                'firstDay' => $firstDay,
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
        return $this->render(
            'front/day.html.twig',
            []
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
