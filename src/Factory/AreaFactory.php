<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Factory;

use App\Entity\Area;
use App\Provider\DateProvider;

class AreaFactory
{
    public static function createNew(): Area
    {
        return new Area();
    }
}
