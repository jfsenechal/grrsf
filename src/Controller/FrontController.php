<?php

namespace App\Controller;

use App\Form\AreaMenuSelectType;
use App\Repository\AreaRepository;
use App\Repository\EntryRepository;
use App\Repository\RoomRepository;
use App\Service\Calendar;
use App\Service\CalendarDataManager;
use App\Service\CalendarDisplay;
use App\Service\Garbadge;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @var Garbadge
     */
    private $garbadge;

    public function __construct(
        Calendar $calendar,
        CalendarDisplay $calendarDisplay,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        EntryRepository $entryRepository,
        Garbadge $garbadge
    ) {
        $this->calendar = $calendar;
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->entryRepository = $entryRepository;
        $this->calendarDisplay = $calendarDisplay;
        $this->garbadge = $garbadge;
    }

    /**
     * @Route("/front/{month}", name="grr_front_home", methods={"GET"})
     */
    public function index(int $month = null): Response
    {
        if (!$month) {
            $month = date('n');
        }

        $startDate = \DateTimeImmutable::createFromFormat('Y-m-d', '2019-'.$month.'-01');
        $esquare = $this->areaRepository->find(1);

        $this->calendar->createCalendarFromDate($startDate);

        $areas = $this->areaRepository->findAll();
        $entries = $this->entryRepository->findAll();
        //$rooms = $this->roomRepository->findByArea($area);
        dump($esquare);

        $form = $this->createForm(AreaMenuSelectType::class, $esquare, ['area' => $esquare]);
        $calendarDataManager = new CalendarDataManager();
        $calendarDataManager->setEntries($entries);

        $dataMonth = $this->calendarDisplay->oneMonth($startDate, $calendarDataManager);
        $navigationMonth = $this->calendarDisplay->oneMonthNavigation($startDate);
        $nexMonth = $this->calendar->getNextMonth();
        $navigationNextMonth = $this->calendarDisplay->oneMonthNavigation($nexMonth);

        return $this->render(
            'front/index.html.twig',
            [
                'first' => $this->calendar->getFirstDay(),
                'current' => $startDate,
                'areas' => $areas,
                'entries' => $entries,
                'data' => $dataMonth,
                'navigation' => $navigationMonth,
                'navigation2' => $navigationNextMonth,
                'form' => $form->createView(),
            ]
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
