<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 22/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Faker;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Faker\Provider\Base as BaseProvider;

class CarbonProvider extends BaseProvider
{
    /**
     * @return string Random job title.
     */
    public function carbonDateTime(int $year, int $month, int $day, int $hour, int $minute)
    {
        return Carbon::create($year, $month, $day, $hour, $minute)->toDateTime();
    }
}