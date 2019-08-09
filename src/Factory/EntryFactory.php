<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:30.
 */

namespace App\Factory;

use App\Entity\Entry;

class EntryFactory
{
    public static function createNew(): Entry
    {
        return new Entry();
    }

    public static function setDefaultValues(Entry $entry)
    {
        $entry
            ->setTimestamp(new \DateTime())
            ->setModerate(false)
            ->setJours(false)
            ->setCreateBy('jf')
            ->setBeneficiaire('jf')
            ->setTimestamp(new \DateTime())
            ->setOptionReservation(0);
    }
}
