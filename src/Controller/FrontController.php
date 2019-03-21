<?php

namespace App\Controller;

use App\Form\AreaMenuSelectType;
use App\Repository\AreaRepository;
use App\Repository\EntryRepository;
use App\Repository\RoomRepository;
use App\Service\Calendar;
use App\Service\CalendarDataManager;
use App\Service\CalendarDisplay;
use App\Service\CalendarNavigationDisplay;
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

    public function __construct(
        Calendar $calendar,
        CalendarDisplay $calendarDisplay,
        CalendarNavigationDisplay $calendarNavigationDisplay,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        EntryRepository $entryRepository
    ) {
        $this->calendar = $calendar;
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->entryRepository = $entryRepository;
        $this->calendarDisplay = $calendarDisplay;
        $this->calendarNavigationDisplay = $calendarNavigationDisplay;
    }

    /**
     * @Route("/", name="grr_front_home", methods={"GET"})
     */
    public function index(int $month = null): Response
    {
        return $this->render(
            'front/index.html.twig'
        );
    }

    /**
     * @Route("/month/{month}", name="grr_front_month", methods={"GET"})
     */
    public function month(int $month = null): Response
    {
        if (!$month) {
            $month = date('n');
        }

        $startDate = \DateTimeImmutable::createFromFormat('Y-m-d', '2019-'.$month.'-01');
        $esquare = $this->areaRepository->find(1);

        $this->calendar->createCalendarFromDate($startDate);

        $areas = $this->areaRepository->findAll();
        $entries = $this->entryRepository->findAll();

        $form = $this->createForm(AreaMenuSelectType::class, $esquare);

        $calendarDataManager = new CalendarDataManager();
        $calendarDataManager->setEntries($entries);

        $dataMonth = $this->calendarDisplay->oneMonth($startDate, $calendarDataManager);

        $navigation = $this->calendarNavigationDisplay->create($startDate, 2);

        return $this->render(
            'front/month.html.twig',
            [
                'first' => $this->calendar->getFirstDay(),
                'current' => $startDate,
                'areas' => $areas,
                'entries' => $entries,
                'data' => $dataMonth,
                'navigation' => $navigation,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/day/{day}", name="grr_front_day", methods={"GET"})
     */
    public function day(int $day = null): Response
    {
        return $this->render(
            'front/day.html.twig',
            []
        );
    }

    /**
     * @Route("/week/{week}", name="grr_front_week", methods={"GET"})
     */
    public function week(int $week = null): Response
    {
        return $this->render(
            'front/week.html.twig',
            []
        );
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
