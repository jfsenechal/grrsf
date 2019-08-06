<?php

namespace App\Service;

use App\Entity\Area;
use App\Factory\CarbonFactory;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class AreaService
{
    /**
     * @var CarbonFactory
     */
    private $carbonFactory;

    public function __construct(CarbonFactory $carbonFactory)
    {
        $this->carbonFactory = $carbonFactory;
    }

    public function getHoursPeriod(Area $area, CarbonInterface $dayModel): CarbonPeriod
    {
        $heureDebut = $area->getMorningstartsArea();
        $heureFin = $area->getEveningendsArea();
        $resolution = $area->getResolutionArea();

        $debut = $this->carbonFactory::create($dayModel->year, $dayModel->month, $dayModel->day, $heureDebut, 0);
        $fin = $this->carbonFactory::create($dayModel->year, $dayModel->month, $dayModel->day, $heureFin, 0, 0);

        return Carbon::parse($debut)->secondsUntil($fin, $resolution);
    }
}