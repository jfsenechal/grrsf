<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 16:21
 */

namespace App\Service;

use App\Model\Day;
use App\Model\Navigation;
use Twig\Environment;
use Webmozart\Assert\Assert;

class CalendarNavigationDisplay
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

    public function create(\DateTimeInterface $dateTime, int $number = 1)
    {
        Assert::greaterThan($number, 0);
        $navigation = new Navigation();

        $navigation->setNextButton($this->nextButton());
        $navigation->setPreviousButton($this->previousButton());
        $navigation->addMonth($this->month());

        return $this->environment->render(
            'calendar/navigation/_calendar_navigation.html.twig',
            [
                'navigation' => $navigation,
            ]
        );

    }

    public function previousButton()
    {
        $previous = $this->calendar->getPreviousMonth();

        return $this->environment->render(
            'calendar/navigation/_button_previous.html.twig',
            [
                'previous' => $previous,
            ]
        );
    }

    public function nextButton()
    {
        $next = $this->calendar->getNextMonth();

        return $this->environment->render(
            'calendar/navigation/_button_next.html.twig',
            [
                'next' => $next,
            ]
        );
    }

    public function month()
    {
        $allDays = $this->calendar->getAllDaysOfMonth();
        $days = $this->getDays($allDays);
        $current = $this->calendar->getOriginalDate();

        return $this->environment->render(
            'calendar/navigation/_month.html.twig',
            [
                'days' => $days,
                'current' => $current,
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
        foreach ($data as $date) {
            $day = new Day($date);
            $days[] = $day;
        }

        return $days;
    }

}