<?php

namespace App\Factory;

use App\Service\LocalHelper;
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

    public static function getToday()
    {
        return Carbon::today()->locale(LocalHelper::getDefaultLocal());
    }

    public static function create($year = 0, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0)
    {
        return Carbon::create()->locale(LocalHelper::getDefaultLocal());
    }

    public static function createImmutable($year = 0, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0)
    {
        return CarbonImmutable::create()->locale(LocalHelper::getDefaultLocal());
    }

    /**
     * start/end of week force
     */
    public static function setStartEndWeek(CarbonInterface $mutable)
    {
        $mutable->startOfWeek(Carbon::MONDAY);
        $mutable->startOfWeek(Carbon::SUNDAY);
    }
}