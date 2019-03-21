<?php

namespace App\Controller;

use App\Repository\AreaRepository;
use App\Repository\EntryRepository;
use App\Repository\RoomRepository;
use App\Service\Calendar;
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
        $allDays = $this->calendar->getAllDaysOfMonth();

        foreach ($allDays as $day) {
            //    dump($day);
            $entries = $this->entryRepository->search2($day, $esquare);
            // $this->garbadge->addEntries($day, $entries);
        }

        $areas = $this->areaRepository->findAll();
        $entries = $this->entryRepository->findAll();
        //$rooms = $this->RoomRepository->findByArea($area);
        dump($entries);

        // $form = $this->createForm(AreaMenuSelectType::class, ['area' => $esquare]);

        $oneMonth = $this->calendarDisplay->oneMonth($startDate, $entries);

        return $this->render(
            'front/index.html.twig',
            [
                'first' => $this->calendar->getFirstDay(),
                'current' => $startDate,
                'areas' => $areas,
                'entries' => $entries,
                'html' => $oneMonth,
                'form' => '',

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
