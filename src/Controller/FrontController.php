<?php

namespace App\Controller;

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

    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @Route("/front/{month}", name="grr_front_home", methods={"GET"})
     */
    public function index(int $month = 3): Response
    {
        $t = \DateTimeImmutable::createFromFormat('Y-m-d', '2019-'.$month.'-01');
        $this->calendar->createCalendarFromDate($t);
        $next = $this->calendar->getNextMonth();
        $previous = $this->calendar->getPreviousMonth();

        return $this->render(
            'front/index.html.twig',
            [
                'first' => $this->calendar->getFirstDay(),
                'current' => $t,
                'next' => $next,
                'previous' => $previous,
                'range' => $this->calendar->getAllDaysOfMonth(),
            ]
        );
    }

}
