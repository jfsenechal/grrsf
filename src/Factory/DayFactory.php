<?php


namespace App\Factory;


use App\Model\Day;
use Carbon\CarbonInterface;

class DayFactory
{
    public function __construct(CarbonFactory $carbonFactory)
    {
        $this->carbonFactory = $carbonFactory;
    }

    public function createImmutable(int $year, int $month, int $day): Day
    {
        $date = $this->carbonFactory->createImmutable($year, $month, $day);
        $day = new Day($date);

        return $day;
    }

    public function createFromCarbon(CarbonInterface $carbon): Day
    {
        return new Day($carbon);
    }
}