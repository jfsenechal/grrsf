<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Factory;

use App\Entity\Repeat;

class RepeatFactory //implements FactoryInterface
{
    /**
     * @return Repeat
     */
    public function createNew(): Repeat
    {
        return new Repeat();
    }
}
