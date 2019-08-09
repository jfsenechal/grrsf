<?php

namespace App\Factory;

use App\Helper\LocalHelper;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class CarbonFactory
{
    /**
     * @var LocalHelper
     */
    private $localHelper;

    public function __construct(LocalHelper $localHelper)
    {
        $this->localHelper = $localHelper;
    }

    public static function getToday(): CarbonInterface
    {
        $date = Carbon::today();
        $date->locale(LocalHelper::getDefaultLocal());

        return $date;
    }

    public static function getTodayImmutable(): CarbonImmutable
    {
        $date = Carbon::today();
        $date->locale(LocalHelper::getDefaultLocal());

        return $date->toImmutable();
    }

    public static function create($year = 0, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0): Carbon
    {
        $date = Carbon::create($year, $month, $day, $hour, $minute, $second);
        $date->locale(LocalHelper::getDefaultLocal());

        return $date;
    }

    public static function createImmutable(
        $year = 0,
        $month = 1,
        $day = 1,
        $hour = 0,
        $minute = 0,
        $second = 0
    ): CarbonImmutable {
        $date = CarbonImmutable::create(
            $year,
            $month,
            $day,
            $hour,
            $minute,
            $second
        );
        $date->locale(LocalHelper::getDefaultLocal());

        return $date;
    }

    /**
     * start/end of week force.
     */
    public static function setStartEndWeek(CarbonInterface $mutable)
    {
        $mutable->startOfWeek(Carbon::MONDAY);
        $mutable->startOfWeek(Carbon::SUNDAY);
    }
}
