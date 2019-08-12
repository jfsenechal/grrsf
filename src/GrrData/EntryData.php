<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 27/02/19
 * Time: 16:59.
 */

namespace App\GrrData;

class EntryData
{
    const UNIT_TIME_MINUTES = 1;
    const UNIT_TIME_HOURS = 2;
    const UNIT_TIME_DAYS = 3;
    const UNIT_TIME_WEEKS = 4;

    /**
     * Encodage de la date de fin de l'entry.
     *
     * @return array
     */
    public function getUnitsTime()
    {
        $units = [
            self::UNIT_TIME_MINUTES => 'unit.minutes',
            self::UNIT_TIME_HOURS => 'unit.hours',
            self::UNIT_TIME_DAYS => 'unit.days',
            self::UNIT_TIME_WEEKS => 'unit.weeks',
        ];

        return $units;
    }
}
