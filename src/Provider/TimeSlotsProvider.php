<?php

namespace App\Provider;

use App\Entity\Area;
use App\Entity\Entry;
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
     * Crée les tranches d'heures sous forme d'objet.
     *
     * @param Area $area
     * @param CarbonInterface $daySelected
     * @return TimeSlot[]
     */
    public function getTimeSlotsModelByAreaAndDay(Area $area, CarbonInterface $daySelected = null)
    {
        $hourBegin = $area->getStartTime();
        $hourEnd = $area->getEndTime();
        $duration = $area->getDurationTimeSlot();

        $timeSlots = $this->getTimeSlots($hourBegin, $hourEnd, $duration);

        $hours = [];
        $timeSlots->rewind();
        $last = $timeSlots->last();
        $timeSlots->rewind();

        while ($timeSlots->current()->lessThan($last)) {
            $begin = $timeSlots->current();
            $timeSlots->next();
            $end = $timeSlots->current();

            $hour = new TimeSlot($begin, $end);

            $hours[] = $hour;
        }

        return $hours;
    }

    /**
     * Retourne les tranches d'heures d'après une heure de début, de fin et d'un interval de temps
     * @param int $hourBegin
     * @param int $hourEnd
     * @param int $duration
     * @param CarbonInterface $dayModel
     *
     * @return CarbonPeriod
     */
    public function getTimeSlots(
        int $hourBegin,
        int $hourEnd,
        int $duration,
        CarbonInterface $dayModel = null
    ): CarbonPeriod {
        if (!$dayModel) {
            $dayModel = Carbon::today();
        }
        $debut = $this->carbonFactory->create($dayModel->year, $dayModel->month, $dayModel->day, $hourBegin, 0);
        $fin = $this->carbonFactory->create($dayModel->year, $dayModel->month, $dayModel->day, $hourEnd, 0, 0);

        return Carbon::parse($debut)->secondsUntil($fin, $duration);
    }

    /**
     * Obtient les tranches horaires de l'entrée basée sur la résolution de l'Area
     * @param Entry $entry
     * @return CarbonPeriod
     */
    public function getTimeSlotsOfEntry(Entry $entry): CarbonPeriod
    {
        $area = $entry->getRoom()->getArea();
        $entryHourBegin = $entry->getStartTime();
        $entryHourEnd = $entry->getEndTime();

        return Carbon::parse($entryHourBegin)->secondsUntil($entryHourEnd, $area->getDurationTimeSlot());
    }
}
