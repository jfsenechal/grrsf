<?php


namespace App\Service;


use App\Entity\Area;
use App\Entity\Entry;
use App\Model\Hour;
use Carbon\Carbon;

class EntryService
{
    public function setCountCells(Entry $entry, Area $area)
    {
        $resolution = $area->getResolutionArea();
        $start = Carbon::instance($entry->getStartTime());
        $end = Carbon::instance($entry->getEndTime());
        $diff = $start->diffInSeconds($end);
        $cellules = (integer)(ceil($diff / $resolution));
        $entry->setCellules($cellules);
    }

    /**
     * @param Entry $entry
     * @param Hour[] $hours
     */
    public function setLocations(Entry $entry, array $hours)
    {
        $locations = [];

        foreach ($hours as $hour) {
            $begin = $hour->getBegin();
            $end = $hour->getEnd();
            if ($this->getEntry($entry, $begin, $end)) {
                $locations[] = $hour;
            }
        }
        $entry->setLocations($locations);
    }

    protected function getEntry(Entry $entry, \DateTimeInterface $begin, \DateTimeInterface $end): bool
    {
        if ($begin->format('H:i') >= $entry->getStartTime()->format('H:i') &&
            $begin->format('H:i') <= $entry->getEndTime()->format('H:i')) {

            //  dump($begin->format('H:i'), '>=', $entry->getStartTime()->format('H:i'));
            //  dump($end->format('H:i'), '<=', $entry->getEndTime()->format('H:i'));

            return true;
        }

        return false;
    }
}