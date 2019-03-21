<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\Area;
use App\Entity\Setting;
use App\GrrData\DateUtils;

class SettingFactory
{
    public function createNew(): Setting
    {
        return new Setting();
    }

    public function setDefaultValues(Area $grrArea)
    {
        $grrArea->setDisplayDays(array_flip(DateUtils::getJoursSemaine()));
        $grrArea->setMorningstartsArea(8);
        $grrArea->setEveningendsArea(19);
        $grrArea->setResolutionArea(900);
        $grrArea->setDureeParDefautReservationArea(900);
    }
}