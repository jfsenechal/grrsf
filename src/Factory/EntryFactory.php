<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:30.
 */

namespace App\Factory;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use Carbon\Carbon;

class EntryFactory
{
    public static function createNew(): Entry
    {
        return new Entry();
    }

    public function initEntryForNew(
        Area $area,
        Room $room,
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute
    ): Entry {
        $date = Carbon::create($year, $month, $day, $hour, $minute);
        $entry = EntryFactory::createNew();
        EntryFactory::setDefaultValues($entry);
        $entry->setArea($area);
        $entry->setRoom($room);
        $entry->setStartTime($date);
        $suite = $date->copy()->addSeconds($area->getDureeParDefautReservationArea());
        $entry->setEndTime($suite);

        return $entry;
    }

    /**
     * @deprecated
     * @param Entry $entry
     */
    public static function setDefaultValues(Entry $entry)
    {
        $entry
            ->setModerate(false)
            ->setJours(false)
            ->setCreateBy('jf')
            ->setBeneficiaire('jf')
            ->setOptionReservation(0);
    }
}
