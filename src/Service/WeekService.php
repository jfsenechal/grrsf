<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 25/03/19
 * Time: 20:25
 */

namespace App\Service;


use Webmozart\Assert\Assert;

class WeekService
{
    /**
     *
     * $date = new DateTime('first day of this month');
     * $date->modify('monday this week');
     */

    /**
     * @param int $year
     * @param int $week
     * @return \DatePeriod
     * @throws \Exception
     */
    public function getDays(int $year, int $week)
    {
        Assert::greaterThan($year, 0);
        Assert::greaterThan($week, 0);
        dump($year);
        dump($week);
        $startDate = new \DateTime();
        $startDate->setISODate($year, $week);

        $endDate = clone($startDate);
        $endDate->modify('+7 days');

        $interval = new \DateInterval('P1D');

        return new \DatePeriod($startDate, $interval, $endDate);
    }
}