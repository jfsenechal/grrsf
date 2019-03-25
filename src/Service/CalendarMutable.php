<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/03/19
 * Time: 16:22
 */

namespace App\Service;

class CalendarMutable
{
    /**
     * @var \DateTime
     */
    protected $dateTime;

    public function createCalendarFromDate(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setDateTime(\DateTimeInterface $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getFirstDay(): \DateTimeInterface
    {
        return $this->dateTime->modify('first day of this month');
    }

    public function getLastDay(): \DateTimeInterface
    {
        return $this->dateTime->modify('last day of this month');
    }

    public function getNextMonth(): \DateTimeInterface
    {
        return $this->dateTime->modify('last day of 1 month');
    }

    public function getPreviousMonth(): \DateTimeInterface
    {
        return $this->dateTime->modify('previous month');
    }

    /**
     * @return \DatePeriod
     * @throws \Exception
     */
    public function getAllDaysOfMonth(): \DatePeriod
    {
        $interval = new \DateInterval('P1D');
        $end = $this->getLastDay();
        $end = $end->modify('+1 day');//to be included
        dump($this->getFirstDay(), $end);

        return new \DatePeriod($this->getFirstDay(), $interval, $end);
    }
}