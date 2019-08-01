<?php

namespace App\Factory;

use App\Service\LocalHelper;
use Carbon\Carbon;

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
}