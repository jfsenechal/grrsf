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
use App\Model\Navigation;
use Carbon\CarbonInterface;
use Twig\Environment;
use Webmozart\Assert\Assert;

class CalendarNavigationDisplay
{
    /**
     * @var Environment
     */
    private $twigEnvironment;
    /**
     * @var Month
     */
    private $month;
    /**
     * @var int
     */
    private $monthNumeric;

    public function __construct(Environment $environment)
    {
        $this->twigEnvironment = $environment;
    }

    public function createMonth(Month $month, int $number = 1)
    {
        $this->month = $month;

        Assert::greaterThan($number, 0);
        //todo set in constructor
        $navigation = new Navigation();

        $navigation->setNextButton($this->nextButton());
        $navigation->setPreviousButton($this->previousButton());

        $current = $this->month->getFirstDayImmutable()->toMutable();

        for ($i = 0; $i < $number; $i++) {
            $navigation->addMonth($this->generateMonth($current));
            $current->addMonth();
        }

        return $this->twigEnvironment->render(
            'calendar/navigation/_calendar_navigation.html.twig',
            [
                'navigation' => $navigation,
            ]
        );

    }

    public function previousButton()
    {
        $previousMonth = $this->month->subMonth();

        return $this->twigEnvironment->render(
            'calendar/navigation/_button_previous.html.twig',
            [
                'previousMonth' => $previousMonth,
                'month' => $this->monthNumeric,
            ]
        );
    }

    public function nextButton()
    {
        $nextMonth = $this->month->addMonth();

        return $this->twigEnvironment->render(
            'calendar/navigation/_button_next.html.twig',
            [
                'nextMonth' => $nextMonth,
                'month' => $this->monthNumeric,
            ]
        );
    }

    public function generateMonth(CarbonInterface $month)
    {
        $firstDay = $month->firstOfMonth();

        $weeks = $this->month->getCalendarWeeks();

        return $this->twigEnvironment->render(
            'calendar/navigation/_month.html.twig',
            [
                'firstDay' => $firstDay,
                'listDays' => DateUtils::getDays(),
                //'weeks'=>$this->month->getDaysGroupByWeeks(),
                'weeks' => $weeks,
            ]
        );
    }
}