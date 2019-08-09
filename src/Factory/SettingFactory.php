<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
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

    public function setDefaultValues(Area $area)
    {
        $area->setDisplayDays(array_flip(DateUtils::getDays()));
        $area->setMorningstartsArea(8);
        $area->setEveningendsArea(19);
        $area->setResolutionArea(900);
        $area->setDureeParDefautReservationArea(900);
    }
}
