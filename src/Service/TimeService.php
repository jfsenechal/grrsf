<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 31/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use Carbon\CarbonInterface;

class TimeService
{
    public static function convertMinutesToHour(int $hours, int $minutes): float
    {
        $reste = $minutes - ($hours * CarbonInterface::MINUTES_PER_HOUR);
        $heureRestante = round($reste / CarbonInterface::MINUTES_PER_HOUR, 2);

        return $hours + $heureRestante;
    }
}
