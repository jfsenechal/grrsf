<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 16:21
 */

namespace App\Service;

use App\Entity\Area;
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

    /**
     * @var Area
     */
    private $area;

    /**
     * @var int
     */
    private $month;

    public function __construct(Calendar $calendar, Environment $environment)
    {
        $this->environment = $environment;
        $this->calendar = $calendar;
    }

    public function init(Area $area, int $month)
    {

    }

    public function create(Area $area, int $month, int $number = 1)
    {
        $this->area = $area;
        $this->month = $month;

        Assert::greaterThan($number, 0);
        //todo extract
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
                'area' => $this->area,
                'month' => $this->month,
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
                'area' => $this->area,
                'month' => $this->month,
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
                'area' => $this->area,
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