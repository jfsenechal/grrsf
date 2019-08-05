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
use App\Model\Navigation;
use Symfony\Component\HttpFoundation\RequestStack;
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
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(Environment $environment, RequestStack $requestStack)
    {
        $this->twigEnvironment = $environment;
        $this->requestStack = $requestStack;
    }

    public function createMonth(Month $month, int $number = 1): Navigation
    {
        $this->month = $month;

        Assert::greaterThan($number, 0);
        //todo set in constructor
        $navigation = new Navigation();

        $navigation->setNextButton($this->nextButton());
        $navigation->setPreviousButton($this->previousButton());

        $current = $this->month->getFirstDayImmutable()->toMutable();

        for ($i = 0; $i < $number; $i++) {
            $monthModel = Month::createJf($current->year, $current->month);
            $navigation->addMonth($this->generateMonthByWeeks($monthModel));
            $current->addMonth();
        }

        return $navigation;

    }

    public function previousButton()
    {
        $previousMonth = $this->month->previousMonth();

        return $this->twigEnvironment->render(
            'calendar/navigation/month/_button_previous.html.twig',
            [
                'previousMonth' => $previousMonth,
                'month' => $this->monthNumeric,
            ]
        );
    }

    public function nextButton()
    {
        $nextMonth = $this->month->nexMonth();

        return $this->twigEnvironment->render(
            'calendar/navigation/month/_button_next.html.twig',
            [
                'nextMonth' => $nextMonth,
                'month' => $this->monthNumeric,
            ]
        );
    }

    public function generateMonthByWeeks(Month $month)
    {
        $firstDay = $month->firstOfMonth();

        $weeks = $this->month->getCalendarWeeks();
        $request = $this->requestStack->getMasterRequest();
        $weekSelected = $request ? $request->get('week') : 0;
        $daySelected = $request ? $request->get('day') : 0;

        return $this->twigEnvironment->render(
            'calendar/navigation/month/_month_by_weeks.html.twig',
            [
                'firstDay' => $firstDay,
                'listDays' => DateUtils::getDays(),
                'weeks' => $weeks,
                'weekSelected' => $weekSelected,
                'daySelected' => $daySelected,
            ]
        );
    }

    public function generateMonthByDay(Month $month)
    {
        $firstDay = $month->firstOfMonth();
        $days = $month->getCalendarDays();

        $request = $this->requestStack->getMasterRequest();
        $weekSelected = $request ? $request->get('week') : 0;
        $daySelected = $request ? $request->get('day') : 0;

        return $this->twigEnvironment->render(
            'calendar/navigation/month/_month_by_days.html.twig',
            [
                'firstDay' => $firstDay,
                'listDays' => DateUtils::getDays(),
                'days' => $days,
                'weekSelected' => $weekSelected,
                'daySelected' => $daySelected,
            ]
        );
    }
}