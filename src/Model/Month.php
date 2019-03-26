<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/03/19
 * Time: 16:22
 */

namespace App\Model;

class Month
{
    /**
     * @var \DateTimeImmutable
     */
    protected $dateTimeImmutable;

    public function createCalendarFromDate(\DateTimeImmutable $dateTime): self
    {
        $this->dateTimeImmutable = $dateTime;

        return $this;
    }

    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTimeImmutable;
    }

    /**
     * @return \DatePeriod
     * @throws \Exception
     */
    public function getDays(): \DatePeriod
    {
        $interval = new \DateInterval('P1D');
        $end = $this->getLastDay();
        $end = $end->modify('+1 day');//to be included

        return new \DatePeriod($this->getFirstDay(), $interval, $end);
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
}