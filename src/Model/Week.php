<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 25/03/19
 * Time: 20:25
 */

namespace App\Model;

use Webmozart\Assert\Assert;

class Week
{
    /**
     *
     * $date = new DateTime('first day of this month');
     * $date->modify('monday this week');
     */

    /**
     * @var \DateTimeImmutable
     */
    protected $startDate;

    /**
     * @var \DateTimeImmutable
     */
    protected $endDate;

    public function create(int $year, int $week): self
    {
        Assert::greaterThan($year, 0);
        Assert::greaterThan($week, 0);

        $date = new \DateTime();
        $date->setISODate($year, $week);

        $this->startDate = $date;

        $this->endDate = clone($this->startDate);
        $this->endDate->modify('+6 days');

        return $this;
    }

    /**
     * @return \DatePeriod
     * @throws \Exception
     */
    public function getDays(): \DatePeriod
    {
        $interval = new \DateInterval('P1D');
        $end = clone $this->endDate;
        $end->modify('+1 days');//to include

        return new \DatePeriod($this->startDate, $interval, $end);
    }

    public function getFirstDay(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function getLastDay(): \DateTimeInterface
    {
        return $this->endDate;
    }
}