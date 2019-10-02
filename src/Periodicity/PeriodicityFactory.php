<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Periodicity;

use App\Entity\Entry;
use App\Entity\Periodicity;

class PeriodicityFactory
{
    public function createNew(Entry $entry): Periodicity
    {
        return new Periodicity($entry);
    }
}
