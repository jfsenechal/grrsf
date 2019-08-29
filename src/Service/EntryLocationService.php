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
     * Fixe les emplacements de l'entry pour la vue sur une journee
     * @param Entry      $entry
     * @param TimeSlot[] $dayTimeSlots les tranches horaires de la journée
     */
    public function setLocations(Entry $entry, array $dayTimeSlots)
    {
        $locations = [];
        $entryTimeSlots = $this->timeSlotsProvider->getTimeSlotsOfEntry($entry);

        foreach ($dayTimeSlots as $dayTimeSlot) {
            $startTimeSlot = $dayTimeSlot->getBegin();
            $endTimeSlot = $dayTimeSlot->getEnd();

            if ($this->isEntryInTimeSlot($entryTimeSlots, $startTimeSlot, $endTimeSlot)) {
                $locations[] = $dayTimeSlot;
            }
        }
        $entry->setLocations($locations);
    }

    protected function isEntryInTimeSlot(CarbonPeriod $entryTimeSlots, \DateTimeInterface $startTimeSlot, \DateTimeInterface $endTimeSlot): bool
    {
        foreach ($entryTimeSlots as $entryTimeSlot) {
            //si tranche 09:00-10:00 et event commence a 10:00, not include
            $entryTimeSlot->addMicrosecond();
            if ($entryTimeSlot->between($startTimeSlot, $endTimeSlot)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Bug si dateEnd entry > dateEndArea.
     * @deprecated
     * @param Entry $entry
     * @param Area  $area
     */
    public function setCountCells(Entry $entry, Area $area)
    {
        $resolution = $area->getTimeInterval();
        $start = Carbon::instance($entry->getStartTime());
        $end = Carbon::instance($entry->getEndTime());
        $diff = $start->diffInSeconds($end);
        $cellules = (int) (ceil($diff / $resolution));
        $entry->setCellules($cellules);
    }

}
