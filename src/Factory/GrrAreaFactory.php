<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\GrrArea;
use App\GrrData\DateUtils;

class GrrAreaFactory
{
    /**
     * @var DateUtils
     */
    private $dateUtils;

    public function __construct(DateUtils $dateUtils)
    {
        $this->dateUtils = $dateUtils;
    }

    public function createNew(): GrrArea
    {
        return new GrrArea();
    }

    public function setDefaultValues(GrrArea $grrArea)
    {
        $grrArea
            ->setDisplayDays(array_flip($this->dateUtils->getJoursSemaine()))
            ->setMorningstartsArea(8)
            ->setEveningendsArea(19)
            ->setResolutionArea(900)
            ->setDureeParDefautReservationArea(900)
            ->setDureeMaxResaArea(-1)
            ->setDureeParDefautReservationArea(0)
            ->setIdTypeParDefaut(-1)
            ->setMaxBooking(-1)
            ->setEnablePeriods(false)
            ->setCalendarDefaultValues(true)
            ->setTwentyfourhourFormatArea(0)
            ->setWeekstartsArea(0)
            ->setEveningendsMinutesArea(0)
            ->setResolutionArea(0)
            ->setEveningendsArea(0)
            ->setMorningstartsArea(0)
            ->setOrderDisplay(0)
            ->setAccess(false);
    }
}