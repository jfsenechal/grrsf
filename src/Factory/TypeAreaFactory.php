<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\GrrTypeArea;

class TypeAreaFactory
{
    public function createNew(): GrrTypeArea
    {
        return new GrrTypeArea();
    }

    public function setDefaultValues(GrrTypeArea $typeArea)
    {
        $typeArea
            ->setOrderDisplay(0)
            ->setDisponible(2);
    }
}