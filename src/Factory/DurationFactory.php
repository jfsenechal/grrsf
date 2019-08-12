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
use Carbon\Carbon;

class DurationFactory
{
    public static function createNew(): DurationModel
    {
        return new DurationModel();
    }

    public static function createByEntry(Entry $entry): DurationModel
    {
        $duration = self::createNew();

        $startTime = Carbon::instance($entry->getStartTime());
        $endTime = Carbon::instance($entry->getEndTime());

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
            $duration->setTime($hours.'.'.($minutes - $hours * 60));
        }
        if ($days > 0) {
            $duration->setUnit(DurationModel::UNIT_TIME_DAYS);
            $duration->setTime($days);
        }
        if ($weeks > 0) {
            $duration->setUnit(DurationModel::UNIT_TIME_WEEKS);
            $duration->setTime($weeks);
        }

        return $duration;
    }
}
