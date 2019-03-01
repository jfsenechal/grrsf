<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\GrrEntry;

class EntryFactory implements FactoryInterface
{
    public function createNew():GrrEntry
    {
        return new GrrEntry();
    }
}