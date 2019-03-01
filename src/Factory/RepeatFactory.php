<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;

use App\Entity\GrrRepeat;

class RepeatFactory //implements FactoryInterface
{
    /**
     * @return GrrRepeat
     */
    public function createNew() : GrrRepeat
    {
        return new GrrRepeat();
    }
}