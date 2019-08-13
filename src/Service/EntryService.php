<?php

namespace App\Service;

use App\Entity\Area;
use App\Entity\Entry;
use App\Model\TimeSlot;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EntryService
{
    /**
     * Bug si dateEnd entry > dateEndArea.
     * @deprecated
     * @param Entry $entry
     * @param Area  $area
     */
    public function setCountCells(Entry $entry, Area $area)
    {
        $resolution = $area->getResolutionArea();
        $start = Carbon::instance($entry->getStartTime());
        $end = Carbon::instance($entry->getEndTime());
        $diff = $start->diffInSeconds($end);
        $cellules = (int) (ceil($diff / $resolution));
        $entry->setCellules($cellules);
    }

    /**
     * Fixe les emplacements de l'entry pour la vue sur une journee
     * @param Entry      $entry
     * @param TimeSlot[] $dayTimeSlots les tranches horaires de la journée
     */
    public function setLocations(Entry $entry, array $dayTimeSlots)
    {
        $locations = [];
        $entryTimeSlots = $this->getTimeSlots($entry);

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
     * Obtient les tranches horaires de l'entrée basée sur la résolution de l'Area
     * @param Entry $entry
     * @return CarbonPeriod
     */
    public function getTimeSlots(Entry $entry): CarbonPeriod
    {
        $area = $entry->getRoom()->getArea();
        $entryHourBegin = $entry->getStartTime();
        $entryHourEnd = $entry->getEndTime();

        return Carbon::parse($entryHourBegin)->secondsUntil($entryHourEnd, $area->getResolutionArea());
    }
}
