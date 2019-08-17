<?php

namespace App\Provider;

use App\Entity\Area;
use App\Factory\CarbonFactory;
use App\Model\TimeSlot;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class TimeSlotsProvider
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
     * Retourne les heures de l'area suivant son resolution.
     *
     * @param Area            $area
     * @param CarbonInterface $dayModel
     *
     * @return CarbonPeriod
     */
    public function getTimeSlots(Area $area, CarbonInterface $dayModel): CarbonPeriod
    {
        $heureDebut = $area->getStartTime();
        $heureFin = $area->getEndTime();
        $resolution = $area->getDurationTimeSlot();

        $debut = $this->carbonFactory::create($dayModel->year, $dayModel->month, $dayModel->day, $heureDebut, 0);
        $fin = $this->carbonFactory::create($dayModel->year, $dayModel->month, $dayModel->day, $heureFin, 0, 0);

        return Carbon::parse($debut)->secondsUntil($fin, $resolution);
    }

    /**
     * Crée les tranches d'heures sous forme d'objet.
     *
     * @param CarbonPeriod $hoursPeriod
     *
     * @return TimeSlot[]
     */
    public function getTimeSlotsModelByAreaAndDay($area, $daySelected)
    {
        $timeSlots = $this->getTimeSlots($area, $daySelected);

        $hours = [];
        $timeSlots->rewind();
        $last = $timeSlots->last();
        $timeSlots->rewind();

        while ($timeSlots->current()->lessThan($last)) {
            $begin = $timeSlots->current();
            $timeSlots->next();
            $end = $timeSlots->current();

            $hour = new TimeSlot();
            $hour->setBegin($begin);
            $hour->setEnd($end);
            $hours[] = $hour;
        }

        return $hours;
    }
}
