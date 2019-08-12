<?php

namespace App\GrrData;

class PeriodicityConstant
{
    const NONE = 0;
    const EVERY_DAY = 1;
    const EVERY_WEEK = 2;
    const EVERY_YEAR = 4;
    const EVERY_MONTH_SAME_DAY = 3;
    const EVERY_MONTH_SAME_WEEK_DAY = 5;
    const LIST_WEEKS_REPEAT = [
        1 => 'periodicity.every_week_repeat',
        2 => 'periodicity.every_week_repeat_2',
        3 => 'periodicity.every_week_repeat_3',
        4 => 'periodicity.every_week_repeat_4',
        5 => 'periodicity.every_week_repeat_5',
    ];

    /**
     * clef de type rep_type_0,rep_type_1,...
     *
     * @return array
     */
    public static function getTypesPeriodicite()
    {
        $vocab = [];
      //  $vocab[self::NONE] = 'periodicity.type.none';
        $vocab[self::EVERY_DAY] = 'periodicity.type.everyday';
        $vocab[self::EVERY_WEEK] = 'periodicity.type.everyweek';
        $vocab[self::EVERY_MONTH_SAME_DAY] = 'periodicity.type.everymonth.sameday';
        $vocab[self::EVERY_MONTH_SAME_WEEK_DAY] = 'periodicity.type.everymonth.sameweek';
        $vocab[self::EVERY_YEAR] = 'periodicity.type.everyyear';

        //$vocab[6] =>'periodicity.type.cycle.days');

        return $vocab;
    }

    public  static function getTypePeriodicite(int $type)
    {
        if (isset(self::getTypesPeriodicite()[$type])) {
            return self::getTypesPeriodicite()[$type];
        }

        return $type;
    }
}
