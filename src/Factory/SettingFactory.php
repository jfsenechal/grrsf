<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Factory;

use App\Entity\Setting;

class SettingFactory
{
    public function createNew(): Setting
    {
        return new Setting();
    }
}
