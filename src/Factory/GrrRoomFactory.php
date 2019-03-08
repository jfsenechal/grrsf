<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\GrrRoom;

class GrrRoomFactory implements FactoryInterface
{
    public function createNew(): GrrRoom
    {
        return new GrrRoom();
    }
}