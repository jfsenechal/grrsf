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
    public function getTimeSlotsModelByAreaAndDaySelected(Area $area, CarbonInterface $daySelected)
    {
        $hourBegin = $area->getStartTime();
        $hourEnd = $area->getEndTime();
        $timeInterval = $area->getTimeInterval();

        $timeSlots = $this->getTimeSlots($daySelected, $hourBegin, $hourEnd, $timeInterval);

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
     * @param CarbonInterface $daySelected
     * @param int $hourBegin
     * @param int $hourEnd
     * @param int $timeInterval
     *
     * @return CarbonPeriod
     */
    public function getTimeSlots(CarbonInterface $daySelected, int $hourBegin, int $hourEnd, int $timeInterval): CarbonPeriod
    {
        $today = $daySelected;

        $debut = $this->carbonFactory->create($today->year, $today->month, $today->day, $hourBegin, 0);
        $fin = $this->carbonFactory->create($today->year, $today->month, $today->day, $hourEnd, 0, 0);

        return Carbon::parse($debut)->secondsUntil($fin, $timeInterval);
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

        return Carbon::parse($entryHourBegin)->secondsUntil($entryHourEnd, $area->getTimeInterval());
    }
}
