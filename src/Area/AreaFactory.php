<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Area;

use App\Entity\Area;

class AreaFactory
{
    public function createNew(): Area
    {
        return new Area();
    }
}
