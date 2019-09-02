<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Factory;

use App\Entity\Entry;
use App\Model\DurationModel;
use App\Service\TimeService;
use Carbon\Carbon;

class DurationFactory
{
    public function createNew(): DurationModel
    {
        return new DurationModel();
    }

    public function createByEntry(Entry $entry): DurationModel
    {
        $duration = $this->createFromDates($entry->getStartTime(), $entry->getEndTime());
        $this->setFullDay($entry, $duration);

        return $duration;
    }

    public function createFromDates(\DateTimeInterface $startTime, \DateTimeInterface $endTime): DurationModel
    {
        $duration = $this->createNew();

        $this->setUnitAndTime($duration, $startTime, $endTime);

        return $duration;
    }

    protected function setUnitAndTime(
        DurationModel $duration,
        \DateTimeInterface $startDateTime,
        \DateTimeInterface $endDateTime
    ) {
        $startTime = Carbon::instance($startDateTime);
        $endTime = Carbon::instance($endDateTime);

        $minutes = $startTime->diffInMinutes($endTime);
        $hours = $startTime->diffInRealHours($endTime);
        $days = $startTime->diffInDays($endTime);
        $weeks = $startTime->diffInWeeks($endTime);

        if ($minutes > 0) {
            $duration->setUnit(DurationModel::UNIT_TIME_MINUTES);
            $duration->setTime($minutes);
        }
        if ($hours > 0) {
            $duration->setUnit(DurationModel::UNIT_TIME_HOURS);
            $hour = TimeService::convertMinutesToHour($hours, $minutes);
            $duration->setTime($hour);
        }
        if ($days > 0) {
            $duration->setUnit(DurationModel::UNIT_TIME_DAYS);
            $duration->setTime($days);
        }
        if ($weeks > 0) {
            $duration->setUnit(DurationModel::UNIT_TIME_WEEKS);
            $duration->setTime($weeks);
        }
    }

    private function setFullDay(Entry $entry, DurationModel $duration)
    {
        $area = $entry->getRoom()->getArea();

        if ($area->getStartTime() === (int) $entry->getStartTime()->format('G') && $area->getEndTime(
            ) === (int) $entry->getEndTime()->format('G')) {
            $duration->setFullDay(true);
        }
    }
}
