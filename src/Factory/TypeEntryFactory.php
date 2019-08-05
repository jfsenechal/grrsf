<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\TypeArea;

class TypeEntryFactory
{
    public static function createNew(): TypeArea
    {
        return new TypeArea();
    }

    public static function setDefaultValues(TypeArea $typeArea)
    {
        $typeArea
            ->setOrderDisplay(0)
            ->setDisponible(2);
    }
}