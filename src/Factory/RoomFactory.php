<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Factory;

use App\Entity\Area;
use App\Entity\Room;

class RoomFactory
{
    public function createNew(Area $area): Room
    {
       return new Room($area);
    }
}
