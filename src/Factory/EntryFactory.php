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
use App\Entity\Periodicity;
use App\Entity\Room;
use Carbon\Carbon;

class EntryFactory
{
    /**
     * @var PeriodicityFactory
     */
    private $periodicityFactory;

    public function __construct(PeriodicityFactory $periodicityFactory)
    {
        $this->periodicityFactory = $periodicityFactory;
    }

    public function createNew(): Entry
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
        $entry = $this->createNew();
        $entry->setArea($area);
        $entry->setRoom($room);
        $entry->setStartTime($date);
        $endTime = $date->copy()->addMinutes($area->getDurationDefaultEntry());
        $entry->setEndTime($endTime);
        $entry->setPeriodicity($this->initPeriodicity($entry));

        return $entry;
    }

    protected function initPeriodicity(Entry $entry): Periodicity
    {
        $periodicity = $this->periodicityFactory->createNew($entry);
        $periodicity->setEndTime($entry->getStartTime());
        $periodicity->setType(null);

        return $periodicity;
    }
}
