<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 16:21
 */

namespace App\Service;

use App\Entity\Entry;
use App\Model\Day;
use App\Model\Month;
use Twig\Environment;

class CalendarDisplay
{
    /**
     * @var Month
     */
    private $month;
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var CalendarDataManager
     */
    private $calendarDataManager;

    public function __construct(Month $month, CalendarDataManager $calendarDataManager, Environment $environment)
    {
        $this->month = $month;
        $this->environment = $environment;
        $this->calendarDataManager = $calendarDataManager;
    }


    /**
     * @param \DateTimeImmutable $dateTimeImmutable
     * @param Entry[] $data
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function oneMonth(\DateTimeImmutable $dateTimeImmutable, CalendarDataManager $calendarDataManager = null)
    {
        $this->month->createCalendarFromDate($dateTimeImmutable);
        $next = $this->month->getNextMonth();
        $previous = $this->month->getPreviousMonth();
        $allDays = $this->month->getDays();
        //pour avoir un iterable
        $days = [];
        foreach ($allDays as $date) {
            $day = new Day($date);
            if ($calendarDataManager) {
                $calendarDataManager->add($day);
            }

            $days[] = $day;
        }

        return $this->environment->render(
            'calendar/data/_calendar_data.html.twig',
            [
                'next' => $next,
                'previous' => $previous,
                'days' => $days,
                'current' => $dateTimeImmutable,
            ]
        );

    }
}