<?php

namespace App\Factory;

use App\Model\Day;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;

class DayFactory
{
    public function __construct(CarbonFactory $carbonFactory)
    {
        $this->carbonFactory = $carbonFactory;
    }

    public function createImmutable(int $year, int $month, int $day): Day
    {
        $date = $this->carbonFactory->createImmutable($year, $month, $day);

        return new Day($date);
    }

    public function createFromCarbon(CarbonInterface $carbon): Day
    {
        return new Day($carbon);
    }
}