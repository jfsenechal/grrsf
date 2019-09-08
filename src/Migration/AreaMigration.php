<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 8/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Migration;


use Carbon\CarbonInterface;

class AreaMigration
{
    /***
     * Transforme un string : yyyynnn en array
     * @param string $display_days
     * @return array
     */
    public static function transformSelecteDays(string $display_days): array
    {
        $pattern = ['#y#', '#n#'];
        $replacements = [1, 0];
        $tab = str_split(strtolower($display_days), 1);
        $days = array_map(
            function ($a) use ($pattern, $replacements) {
                return (int) preg_replace($pattern, $replacements, $a);
            },
            $tab
        );

        return $days;
    }

    public static function transformToMinutes(int $time)
    {
        if ($time <= 0) {
            return 0;
        }

        return $time / CarbonInterface::MINUTES_PER_HOUR;
    }

}