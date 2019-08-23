<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 23/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Service;


class ResultBind
{
    public static function resultNamesMonthWithRoom()
    {
        return [
            "Tous les jours pendant 3 jours",
            "Entry avec une date en commun",
        ];
    }

    public static function resultNamesMonthWithOutRoom()
    {
        return [
            "Entry avec une date en commun",
            "Tous les jours pendant 3 jours",
            "Entry avec une date en commun",
        ];
    }


    public static function getCountEntriesFoMonthWithRoom(int $day): int
    {
        $result = [3 => 1, 4 => 1, 5 => 1, 6 => 1,];

        return $result[$day] ?? 0;
    }

    public static function getCountEntriesFoMonthWithOutRoom(int $day): int
    {
        $result = [3 => 2, 4 => 1, 5 => 1, 6 => 1];

        return $result[$day] ?? 0;
    }

}