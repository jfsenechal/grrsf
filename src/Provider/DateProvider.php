<?php

namespace App\Provider;

use App\I18n\LocalHelper;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class DateProvider
{
    /**
     * Names of days of the week.
     *
     * @return array
     */
    public static function getNamesDaysOfWeek()
    {
        //todo dynamic first day of week
        //https://carbon.nesbot.com/docs/#api-week
        //$en->firstWeekDay); != $fr->firstWeekDay);

        $days = [];
        $translator = \Carbon\Translator::get(
            LocalHelper::getDefaultLocal()
        );//LocalHelper::getDefaultLocal() return 'fr'

        foreach (Carbon::getDays() as $day) {
            $days[] = $translator->trans($day);
        }
        $days = Carbon::getDays();
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
