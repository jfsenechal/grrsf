<?php

namespace App\Factory;

use App\I18n\LocalHelper;
use App\Model\Day;
use Carbon\CarbonInterface;

class DayFactory
{
    /**
     * @var \App\Factory\CarbonFactory|mixed
     */
    public $carbonFactory;
    /**
     * @var LocalHelper
     */
    private $localHelper;

    public function __construct(CarbonFactory $carbonFactory, LocalHelper $localHelper)
    {
        $this->carbonFactory = $carbonFactory;
        $this->localHelper = $localHelper;
    }

    public function createImmutable(int $year, int $month, int $day): Day
    {
        $date = $this->carbonFactory->createImmutable($year, $month, $day);

        $dayModel = new Day($date);
        $dayModel->locale($this->localHelper->getDefaultLocal());

        return $dayModel;
    }

    public function createFromCarbon(CarbonInterface $carbon): Day
    {
        $dayModel = new Day($carbon);
        $dayModel->locale($this->localHelper->getDefaultLocal());

        return $dayModel;
    }
}
