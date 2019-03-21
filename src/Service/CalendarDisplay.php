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

    public function __construct(Calendar $calendar, Environment $environment)
    {
        $this->calendar = $calendar;
        $this->environment = $environment;
    }

    /**
     * @param \DateTimeImmutable $dateTimeImmutable
     * @param Entry[] $data
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function oneMonth(\DateTimeImmutable $dateTimeImmutable, iterable $data = [])
    {
        $this->calendar->createCalendarFromDate($dateTimeImmutable);
        $next = $this->calendar->getNextMonth();
        $previous = $this->calendar->getPreviousMonth();
        $allDays = $this->calendar->getAllDaysOfMonth();
        //pour avoir un iterable
        $days = [];
        foreach ($allDays as $date) {
            $grrDay = new Day($date);
            if ($data) {
                $entries = $data[$date->format('Y-m-d')] ?? null;
                if ($entries) {
                    $grrDay->setEntries($entries);
                }
            }
            $days[] = $grrDay;
        }

        return $this->environment->render(
            'front/_calendars.html.twig',
            [
                'next' => $next,
                'previous' => $previous,
                'days' => $days,
                'current' => $dateTimeImmutable,
            ]
        );

    }
}