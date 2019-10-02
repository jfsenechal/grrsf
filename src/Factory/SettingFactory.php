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
    /**
     * @param string $name
     * @param string|array $value
     * @return Setting
     */
    public function createNew(string $name, $value): Setting
    {
        return new Setting($name, $value);
    }
}
