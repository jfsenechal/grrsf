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

    public function create(Month $month, int $number = 1)
    {
        $this->month = $month;
        $this->monthNumeric = $month->getNumeric();

        Assert::greaterThan($number, 0);
        //todo extract
        $navigation = new Navigation();

        $navigation->setNextButton($this->nextButton());
        $navigation->setPreviousButton($this->previousButton());

        $current = $this->month->getDateTimeImmutable();

        for ($i = 0; $i < $number; $i++) {
            $navigation->addMonth($this->month($month));
            //   $current->modify('+1 month');
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
        $previousMonth = $this->month->getPreviousMonth();

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
        $nextMonth = $this->month->getNextMonth();

        return $this->twigEnvironment->render(
            'calendar/navigation/_button_next.html.twig',
            [
                'nextMonth' => $nextMonth,
                'month' => $this->monthNumeric,
            ]
        );
    }

    public function month(Month $month)
    {
        $firstDay = $month->getFirstDay();
        $allDays = $this->month->getDays();
        $days = $this->getDays($allDays);
        $weeks = $this->month->getWeeks($firstDay);

        return $this->twigEnvironment->render(
            'calendar/navigation/_month.html.twig',
            [
                'days' => $days,
                'firstDay' => $firstDay,
                'listDays' => DateUtils::getJoursSemaine(),
                //'weeks'=>$this->month->getDaysGroupByWeeks(),
                'weeks' => $weeks,
            ]
        );
    }

    /**
     *
     * @param \DateTimeInterface[] $data
     * @return Day[]
     */
    protected function getDays(iterable $data)
    {
        $days = [];
        foreach ($data as $date) {
            $day = new Day($date);
            $days[] = $date;
        }

        return $days;
    }


}