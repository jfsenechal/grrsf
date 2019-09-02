<?php

namespace App\Factory;

use App\I18n\LocalHelper;
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

        $dayModel = new Day($date);
        $dayModel->locale(LocalHelper::getDefaultLocal());

        return $dayModel;
    }

    public function createFromCarbon(CarbonInterface $carbon): Day
    {
        $dayModel = new Day($carbon);
        $dayModel->locale(LocalHelper::getDefaultLocal());

        return $dayModel;
    }
}
