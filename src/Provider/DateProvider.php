<?php


namespace App\Provider;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class DateProvider
{
    /**
     * Names of days of the week.
     * @return array
     */
    public static function getNamesDaysOfWeek()
    {
        //todo translate with carbon ?
        //https://carbon.nesbot.com/docs/#api-week
        //$en->firstWeekDay); != $fr->firstWeekDay);
        $days = Carbon::getDays();
        //todo dynamic
        //if lundi first, on pousse dimanche a la fin

        $days[] = $days[0];
        unset($days[0]);

        return $days;
    }

    public static function getHours()
    {
        return range(1, CarbonInterface::HOURS_PER_DAY);
    }
}