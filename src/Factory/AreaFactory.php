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

class AreaFactory
{
    public function createNew(): GrrArea
    {
        return new GrrArea();
    }

    public function setDefaultValues(GrrArea $grrArea)
    {
        $grrArea->setDisplayDays(array_flip(DateUtils::getJoursSemaine()));
        $grrArea->setMorningstartsArea(8);
        $grrArea->setEveningendsArea(19);
        $grrArea->setResolutionArea(900);
        $grrArea->setDureeParDefautReservationArea(900);
    }
}