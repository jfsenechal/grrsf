<?php


namespace App\Provider;


use Carbon\Carbon;

class DateProvider
{
    /**
     * Names of days of the week.
     * @return array
     */
    public static function getNamesDaysOfWeek()
    {
        $days = Carbon::getDays();
        //todo dynamic
        //if lundi first, on pousse dimanche a la fin
        $days[] = $days[0];
        unset($days[0]);

        return $days;
    }

    public static function getHours()
    {
        return range(0, 23);
    }
}