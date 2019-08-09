<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Periodicity;
use App\GrrData\DateUtils;

class PeriodicityFactory
{
    /**
     * @var DateUtils
     */
    private $dateUtils;

    public function __construct(DateUtils $dateUtils)
    {
        $this->dateUtils = $dateUtils;
    }

    public static function createNew(Entry $entry): Periodicity
    {
        return new Periodicity($entry);
    }

    public static function setDefaultValues(Area $area)
    {
        $area
            ->setDisplayDays(array_flip(DateUtils::getDays()))
            ->setMorningstartsArea(8)
            ->setEveningendsArea(19)
            ->setResolutionArea(1800)
            ->setDureeParDefautReservationArea(900)
            ->setDureeMaxResaArea(-1)
            ->setIdTypeParDefaut(-1)
            ->setMaxBooking(-1)
            ->setEnablePeriods(false)
            ->setCalendarDefaultValues(true)
            ->setTwentyfourhourFormatArea(0)
            ->setWeekstartsArea(0)
            ->setEveningendsMinutesArea(0)
            ->setOrderDisplay(0)
            ->setAccess(false);
    }
}