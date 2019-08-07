<?php

namespace App\Service;

use App\Entity\Area;
use App\Factory\CarbonFactory;
use App\Model\Hour;
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

    /**
     * Retourne les heures de l'area
     * @param Area $area
     * @param CarbonInterface $dayModel
     * @return CarbonPeriod
     */
    public function getHoursPeriod(Area $area, CarbonInterface $dayModel): CarbonPeriod
    {
        $heureDebut = $area->getMorningstartsArea();
        $heureFin = $area->getEveningendsArea();
        $resolution = $area->getResolutionArea();

        $debut = $this->carbonFactory::create($dayModel->year, $dayModel->month, $dayModel->day, $heureDebut, 0);
        $fin = $this->carbonFactory::create($dayModel->year, $dayModel->month, $dayModel->day, $heureFin, 0, 0);

        return Carbon::parse($debut)->secondsUntil($fin, $resolution);
    }

    /**
     * @param CarbonPeriod $hoursPeriod
     * @return Hour[]
     */
    public function getHoursModel($area, $daySelected)
    {
        $hoursPeriod = $this->getHoursPeriod($area, $daySelected);

        $hours = [];
        $hoursPeriod->rewind();
        $last = $hoursPeriod->last();
        $hoursPeriod->rewind();

        while ($hoursPeriod->current()->lessThan($last)) {

            $begin = $hoursPeriod->current();
            $hoursPeriod->next();
            $end = $hoursPeriod->current();

            $hour = new Hour();
            $hour->setBegin($begin);
            $hour->setEnd($end);
            $hours[] = $hour;
        }

        return $hours;
    }
}