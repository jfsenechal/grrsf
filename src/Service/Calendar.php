<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/03/19
 * Time: 16:22
 */

namespace App\Service;

class Calendar
{
    /**
     * @var \DateTimeImmutable
     */
    protected $originalDate;

    public function createCalendarFromDate(\DateTimeImmutable $dateTime)
    {
        $this->originalDate = $dateTime;
    }

    public function getFirstDay()
    {
        $date = clone $this->originalDate;

        return $date->modify('first day of this month');
    }

    public function getLastDay()
    {
        $date = clone $this->originalDate;

        return $date->modify('last day of this month');
    }

    public function getNextMonth()
    {
        $date = clone $this->originalDate;

        return $date->modify('last day of 1 month');
    }

    /**
     * @return \DatePeriod
     * @throws \Exception
     */
    public function getAllDaysOfMonth()
    {
        $interval = new \DateInterval('P1D');
        $end = $this->getLastDay();
        $end = $end->modify('+1 day');//to be included

        return new \DatePeriod($this->getFirstDay(), $interval, $end);
    }

    public function getPreviousMonth()
    {
        $date = clone $this->originalDate;

        return $date->modify('previous month');
    }
}