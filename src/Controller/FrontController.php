<?php

namespace App\Controller;

use App\Repository\GrrAreaRepository;
use App\Service\Calendar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function __construct(Calendar $calendar, GrrAreaRepository $grrAreaRepository)
    {
        $this->calendar = $calendar;
        $this->grrAreaRepository = $grrAreaRepository;
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
            ]
        );
    }

}
