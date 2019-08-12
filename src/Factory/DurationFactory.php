<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Factory;

use App\Model\DurationModel;

class DurationFactory
{
    public static function createNew(): DurationModel
    {
        return new DurationModel();
    }
}
