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
     * @var Environment
     */
    private $environment;
    /**
     * @var Calendar
     */
    private $calendar;

    public function __construct(Calendar $calendarMutable, Environment $environment)
    {
        $this->environment = $environment;
        $this->calendar = $calendarMutable;
    }

    public function create(int $number = 1)
    {
        Assert::greaterThan($number, 0);
        $navigation = new Navigation();

        $navigation->setNextButton($this->nextButton());
        $navigation->setPreviousButton($this->previousButton());

        $current = $this->calendar->getDateTimeImmutable();

        for ($i = 0; $i < $number; $i++) {
            $navigation->addMonth($this->month($current));
            $current->modify('+1 month');
        }

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

    public function month(\DateTimeInterface $dateTime)
    {
        $allDays = $this->calendar->getAllDaysOfMonth($dateTime);
        $days = $this->getDays($allDays);

        return $this->environment->render(
            'calendar/navigation/_month.html.twig',
            [
                'days' => $days,
                'current' => $dateTime,
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
            $days[] = $day;
        }

        return $days;
    }

}