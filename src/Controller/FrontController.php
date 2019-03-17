<?php

namespace App\Controller;

use App\Form\AreaMenuSelectType;
use App\Repository\GrrAreaRepository;
use App\Repository\GrrRoomRepository;
use App\Service\Calendar;
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
     * @var GrrAreaRepository
     */
    private $grrAreaRepository;
    /**
     * @var GrrRoomRepository
     */
    private $grrRoomRepository;

    public function __construct(
        Calendar $calendar,
        GrrAreaRepository $grrAreaRepository,
        GrrRoomRepository $grrRoomRepository
    ) {
        $this->calendar = $calendar;
        $this->grrAreaRepository = $grrAreaRepository;
        $this->grrRoomRepository = $grrRoomRepository;
    }

    /**
     * @Route("/front/{month}", name="grr_front_home", methods={"GET"})
     */
    public function index(int $month = null): Response
    {
        if (!$month) {
            $month = date('n');
        }
        $t = \DateTimeImmutable::createFromFormat('Y-m-d', '2019-'.$month.'-01');
        $this->calendar->createCalendarFromDate($t);
        $next = $this->calendar->getNextMonth();
        $previous = $this->calendar->getPreviousMonth();
        $allDays = $this->calendar->getAllDaysOfMonth();

        $esquare = $this->grrAreaRepository->find(1);
        $areas = $this->grrAreaRepository->findAll();
        //$rooms = $this->grrRoomRepository->findByArea($area);

        $data = [];
        $form = $this->createForm(
            AreaMenuSelectType::class,
            $data,
            [
                'area' => $esquare,
            ]
        );

        //pour avoir un iterable
        $days = [];
        foreach ($allDays as $day) {
            $days[] = $day;
        }

        return $this->render(
            'front/index.html.twig',
            [
                'first' => $this->calendar->getFirstDay(),
                'current' => $t,
                'next' => $next,
                'previous' => $previous,
                'range' => $days,
                'areas' => $areas,
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
        $area = $this->grrAreaRepository->find($areaId);
        $rooms = $this->grrRoomRepository->findByArea($area);

        return $this->render('front/_rooms_options.html.twig', ['rooms' => $rooms]);
    }

}
