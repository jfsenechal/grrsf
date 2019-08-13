<?php


namespace App\Service;


use App\Entity\PeriodicityDay;
use Carbon\Carbon;

class GeneratorEntry
{
    /**
     *
     * @param PeriodicityDay[] $periodicityDays
     * @return array
     */
    public function generateEntries(array $periodicityDays)
    {
        $entries = [];
        foreach ($periodicityDays as $periodicityDay) {
            $entry = clone($periodicityDay->getEntry());
            $date = Carbon::instance($periodicityDay->getDatePeriodicity());

            $startTime = Carbon::instance($entry->getStartTime());
            $startTime->setYear($date->year);
            $startTime->setMonth($date->month);
            $startTime->setDay($date->day);

            $endTime = Carbon::instance($entry->getEndTime());
            $endTime->setYear($date->year);
            $endTime->setMonth($date->month);
            $endTime->setDay($date->day);

            $entry->setStartTime($startTime->toDateTime());
            $entry->setEndTime($endTime->toDateTime());

            $entries[] = $entry;
        }

        return $entries;
    }
}