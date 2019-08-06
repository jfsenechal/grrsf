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
use Twig\Environment;

class MonthHelperDataDisplay
{
    /**
     * @var Environment
     */
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param Month $month
     * @return string
     */
    public function generateHtmlMonth(Month $month)
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