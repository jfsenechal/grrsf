<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\TypeEntry;

use App\Entity\EntryType;

class TypeEntryFactory
{
    public function createNew(): EntryType
    {
        return new EntryType();
    }
}
