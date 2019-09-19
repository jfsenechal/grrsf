<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 22/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Faker;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Faker\Provider\Base as BaseProvider;

/**
 * Util pour le chargement des fixtures lors des tests
 * Class CarbonProvider.
 */
class CarbonProvider extends BaseProvider
{
    /**
     * @return string random job title
     */
    public function carbonDateTime(int $year, int $month, int $day, int $hour, int $minute)
    {
        return Carbon::create($year, $month, $day, $hour, $minute)->toDateTime();
    }

    /**
     * @return string random job title
     */
    public function carbonDate(int $year, int $month, int $day)
    {
        return Carbon::createFromDate($year, $month, $day)->toDateTime();
    }

    /**
     * @return string random job title
     */
    public function carbonFromFormat(string $format, string $date)
    {var_dump($format, $date);
        return CarbonImmutable::createFromFormat($format, $date);
    }

    public function carbonToday(int $hour, int $minute)
    {
        $today = Carbon::today();
        $today->setTime($hour, $minute);

        return $today;
    }
}
