<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 16:21.
 */

namespace App\Helper;

use App\Entity\Area;
use App\Model\Month;
use App\Provider\DateProvider;
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
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function generateHtmlMonth(Month $month, Area $area): string
    {
        $weeks = $month->groupDataDaysByWeeks();

        return $this->environment->render(
            '@grr_front/month/_calendar_data.html.twig',
            [
                'listDays' => DateProvider::getNamesDaysOfWeek(),
                'firstDay' => $month->firstOfMonth(),
                'dataDays' => $month->getDataDays(),
                'weeks' => $weeks,
                'area' => $area, //for legend entry type
            ]
        );
    }
}
