<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 16:21
 */

namespace App\Service;

use App\GrrData\DateUtils;
use App\Model\Month;
use App\Model\Week;
use Twig\Environment;

class CalendarDataDisplay
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
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function generateMonth(Month $month)
    {
        $weeks = $month->groupDataDaysByWeeks();

        return $this->environment->render(
            'calendar/data/_calendar_data.html.twig',
            [
                'listDays' => DateUtils::getDays(),
                'firstDay' => $month->firstOfMonth(),
                'dataDays' => $month->getDataDays(),
                'weeks' => $weeks,
            ]
        );

    }

}