<?php

namespace App\Entry;

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
     * Fixe l'occupation des emplacements de l'entry pour la vue sur une journee.
     *
     * @param Entry $entry
     * @param TimeSlot[] $dayTimeSlots les tranches horaires de la journée
     *
     * @return TimeSlot[]
     */
    public function getLocations(Entry $entry, array $dayTimeSlots)
    {
        /**
         * @var TimeSlot[]
         */
        $locations = [];
        $entryTimeSlots = $this->timeSlotsProvider->getTimeSlotsOfEntry($entry);

        foreach ($dayTimeSlots as $dayTimeSlot) {
            if ($this->isEntryInTimeSlot($entry, $entryTimeSlots, $dayTimeSlot)) {
                $locations[] = $dayTimeSlot;
            }
        }

        return $locations;
    }

    protected function isEntryInTimeSlot(
        Entry $entry,
        CarbonPeriod $entryTimeSlots,
        TimeSlot $dayTimeSlot
    ): bool {
        $startTimeSlot = $dayTimeSlot->getBegin();
        $endTimeSlot = $dayTimeSlot->getEnd();

        /**
         * Use case
         * si tranche 9h30-10h00, entry 9h30-10h00
         */
        foreach ($entryTimeSlots as $entryTimeSlot) {

            if ($entryTimeSlot->between($startTimeSlot, $endTimeSlot)) {
                /*
                 * si la tranche horaire de l'entrée est égale à l'heure de fin de la tranche journalière
                 * 2019-08-05 09:00 compris entre 2019-08-05 08:30 et  2019-08-05 09:00
                 */
                if ($entryTimeSlot->format('H:i') === $endTimeSlot->format('H:i')) {
                    return false;
                }

                /*
                 * 2019-08-08 09:00 compris entre 2019-08-08 09:00 et 2019-08-08 09:30
                 * si l'heure de début de l'entrée est égale à l'heure de fin de la tranche horaire
                 */
                //if ($entryTimeSlot->format('H:i') === $startTimeSlot->format('H:i')) {
                if ($entry->getEndTime()->format('Y-m-d H:i') === $startTimeSlot->format('Y-m-d H:i')) {
                    return false;
                }

                return true;
            }
        }

        return false;
    }
}
