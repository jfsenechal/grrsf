<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 23/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Service;

class ResultBind
{
    public static function resultNamesMonthWithRoom()
    {
        return [
            'Tous les jours pendant 3 jours',
        ];
    }

    public static function resultNamesMonthWithOutRoom()
    {
        return [
            'Tous les jours pendant 3 jours',
            'Entry avec une date en commun autre room',
        ];
    }

    /**
     * 2019-12-02 => 2019-12-03, 2019-12-04, 2019-12-05
     * @param int $day
     * @return int
     */
    public static function getCountEntriesFoMonthWithRoom(int $day): int
    {
        $result = [2 => 1, 3 => 1, 4 => 1, 5 => 1];

        return $result[$day] ?? 0;
    }

    /**
     * 2019-12-02 => 2019-12-03, 2019-12-04, 2019-12-05
     * 2019-12-03 => 2019-12-04, 2019-12-05, 2019-12-06
     *
     * @param int $day
     * @return int
     */
    public static function getCountEntriesFoMonthWithOutRoom(int $day): int
    {
        $result = [2 => 1, 3 => 2, 4 => 2, 5 => 2, 6 => 1];

        return $result[$day] ?? 0;
    }

    public static function getDaysOfWeekWithRoom()
    {
        return [
            '2018-07-02',
            '2018-07-03',
            '2018-07-04',
            '2018-07-05',
            '2018-07-06',
            '2018-07-07',
            '2018-07-08',
        ];
    }

    public static function getDaysOfWeekWitOuthhRoom()
    {
        return [
            '2019-12-02',
            '2019-12-03',
            '2019-12-04',
            '2019-12-05',
            '2019-12-06',
            '2019-12-07',
            '2019-12-08',
        ];
    }

    public static function getCountEntriesForWeekWithMonth(int $day): int
    {
        $result = [2 => 1, 3 => 1];

        return $result[$day] ?? 0;
    }

    /**
     *
     * 2019-12-02 => 2019-12-03, 2019-12-04, 2019-12-05
     * 2019-12-03 => 2019-12-04, 2019-12-05, 2019-12-06
     * @param int $day
     * @param string $room
     * @return int
     */
    public static function getCountEntriesForWeekWithOutMonth(int $day, string $room): int
    {
        $result = [];
        $result['Salle Collège'][2] = 1;
        $result['Salle Collège'][3] = 1;
        $result['Salle Collège'][4] = 1;
        $result['Salle Collège'][5] = 1;
        $result['Salle Conseil'][3] = 1;
        $result['Salle Conseil'][4] = 1;
        $result['Salle Conseil'][5] = 1;
        $result['Salle Conseil'][6] = 1;

        return $result[$room][$day] ?? 0;
    }

    public static function resultNamesWeekWithRoom()
    {
        return [
            'Toutes les semaines, lundi et mardi',
        ];
    }

    public static function resultNamesWeekWithOutRoom()
    {
        return [
            'Tous les jours pendant 3 jours',
            'Entry avec une date en commun autre room'
        ];
    }
}
