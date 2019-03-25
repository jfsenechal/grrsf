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
use Twig\Environment;

class CalendarDisplay
{
    /**
     * @var Calendar
     */
    private $calendar;
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var CalendarDataManager
     */
    private $calendarDataManager;

    public function __construct(Calendar $calendar, CalendarDataManager $calendarDataManager, Environment $environment)
    {
        $this->calendar = $calendar;
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
        $this->calendar->createCalendarFromDate($dateTimeImmutable);
        $next = $this->calendar->getNextMonth();
        $previous = $this->calendar->getPreviousMonth();
        $allDays = $this->calendar->getAllDaysOfMonth();
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