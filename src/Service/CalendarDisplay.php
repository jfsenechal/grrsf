<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 16:21
 */

namespace App\Service;

use App\GrrData\DateUtils;
use App\Model\Day;
use App\Model\Month;
use App\Model\Week;
use Twig\Environment;

class CalendarDisplay
{
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var CalendarDataManager
     */
    private $calendarDataManager;

    public function __construct(CalendarDataManager $calendarDataManager, Environment $environment)
    {
        $this->environment = $environment;
        $this->calendarDataManager = $calendarDataManager;
    }

    /**
     * @param Month $month
     * @param CalendarDataManager|null $calendarDataManager
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function oneMonth(Month $month, CalendarDataManager $calendarDataManager)
    {
        //pour avoir un iterable
        $data = [];
        $allDays = $month->getDays();
        $weeks = $month->getWeeks();
        $i = 0;
        foreach ($weeks as $week) {
            $days = [];
            $data[$i]['week'] = $week;
            foreach ($week as $date) {
                //todo extract
                $day = new Day($date);
                $calendarDataManager->add($day);
                $days [] = $day;
            }
            $data[$i]['days'] = $days;
            $i++;
        }

        return $this->environment->render(
            'calendar/data/_calendar_data.html.twig',
            [
                'listDays' => DateUtils::getJoursSemaine(),
                'firstDay' => $month->getFirstDay(),
                'weeks' => $weeks,
                'datas' => $data,
            ]
        );

    }

    public function oneWeek(Week $week, CalendarDataManager $calendarDataManager)
    {
        $days = [];
        foreach ($week->getDays() as $date) {
            //todo extract
            $day = new Day($date);
            $calendarDataManager->add($day);
            $days[] = $day;
        }

        $week->setDaysData($days);

        return $week;

        return $this->environment->render(
            'calendar/data/_calendar_data.html.twig',
            [
                'days' => $days,
                'firstDay' => $week->getFirstDay(),
            ]
        );
    }
}