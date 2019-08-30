<?php

namespace App\Service;

use App\Entity\Area;
use App\Entity\Entry;
use App\Model\TimeSlot;
use App\Provider\TimeSlotsProvider;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EntryLocationService
{
    /**
     * @var TimeSlotsProvider
     */
    private $timeSlotsProvider;

    public function __construct(TimeSlotsProvider $timeSlotsProvider)
    {
        $this->timeSlotsProvider = $timeSlotsProvider;
    }

    /**
     * Fixe l'occupation des emplacements de l'entry pour la vue sur une journee
     *
     * @param Entry $entry
     * @param TimeSlot[] $dayTimeSlots les tranches horaires de la journée
     * @return TimeSlot[]
     */
    public function getLocations(Entry $entry, array $dayTimeSlots)
    {
        $locations = [];
        $entryTimeSlots = $this->timeSlotsProvider->getTimeSlotsOfEntry($entry);

        foreach ($dayTimeSlots as $dayTimeSlot) {
            $startTimeSlot = $dayTimeSlot->getBegin();
            $endTimeSlot = $dayTimeSlot->getEnd();

            if ($this->isEntryInTimeSlot($entry, $entryTimeSlots, $startTimeSlot, $endTimeSlot)) {
                $locations[] = $dayTimeSlot;
            }
        }
        return $locations;
    }

    protected function isEntryInTimeSlot(
        Entry $entry,
        CarbonPeriod $entryTimeSlots,
        \DateTimeInterface $startTimeSlot,
        \DateTimeInterface $endTimeSlot
    ): bool {

        /**
         * Use case
         * si tranche 9h30-10h00, entry 9h30-10h00
         */

        foreach ($entryTimeSlots as $entryTimeSlot) {

            //  dump( $startTimeSlot, $endTimeSlot);

            if ($entryTimeSlot->between($startTimeSlot, $endTimeSlot)) {

                /**
                 * si l'heure de fin de l'entrée est égale à l'heure de début de la tranche
                 */
                if ($entry->getEndTime()->format('H:i') === $startTimeSlot->format('H:i')) {
                    return false;
                }

                /**
                 * si l'heure de début de l'entrée est égale à l'heure de fin de la tranche horaire
                 */
                if ($entry->getStartTime()->format('H:i') === $endTimeSlot->format('H:i')) {
                    return false;
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Bug si dateEnd entry > dateEndArea.
     * @param Entry $entry
     * @param Area $area
     * @deprecated
     */
    public function setCountCells(Entry $entry, Area $area)
    {
        $resolution = $area->getTimeInterval();
        $start = Carbon::instance($entry->getStartTime());
        $end = Carbon::instance($entry->getEndTime());
        $diff = $start->diffInSeconds($end);
        $cellules = (int)(ceil($diff / $resolution));
        $entry->setCellules($cellules);
    }

}
