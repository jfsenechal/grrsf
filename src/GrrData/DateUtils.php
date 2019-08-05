<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 21:55
 */

namespace App\GrrData;

use Carbon\Carbon;
use Symfony\Contracts\Translation\TranslatorInterface;

class DateUtils
{
    public static function getDays()
    {
        $days = Carbon::getDays();
        //todo dynamic
        //if lundi first, on pousse dimanche a la fin
        $days[] = $days[0];
        unset($days[0]);

        return $days;
    }

    public static function getHeures()
    {
        return range(0, 23);
    }

}