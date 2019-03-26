<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/03/19
 * Time: 16:22
 */

namespace App\Service\Calendar;

class MonthCalendar
{
    /**
     * @var \DateTimeImmutable
     */
    protected $dateTimeImmutable;

    public function createCalendarFromDate(\DateTimeImmutable $dateTime)
    {
        $this->dateTimeImmutable = $dateTime;
    }

    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTimeImmutable;
    }

    public function getFirstDay(): \DateTimeInterface
    {
        $date = clone $this->dateTimeImmutable;

        return $date->modify('first day of this month');
    }

    public function getLastDay(): \DateTimeInterface
    {
        $date = clone $this->dateTimeImmutable;

        return $date->modify('last day of this month');
    }

    public function getNextMonth(): \DateTimeInterface
    {
        $date = clone $this->dateTimeImmutable;

        return $date->modify('last day of 1 month');
    }

    public function getPreviousMonth(): \DateTimeInterface
    {
        $date = clone $this->dateTimeImmutable;

        return $date->modify('previous month');
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

        return new \DatePeriod($this->getFirstDay(), $interval, $end);
    }
}