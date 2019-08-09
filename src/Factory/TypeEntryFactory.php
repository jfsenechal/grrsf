<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Factory;

use App\Entity\EntryType;

class TypeEntryFactory
{
    public static function createNew(): EntryType
    {
        return new EntryType();
    }

    public static function setDefaultValues(EntryType $typeArea)
    {
        $typeArea
            ->setOrderDisplay(0)
            ->setDisponible(2);
    }
}
