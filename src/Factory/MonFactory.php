<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;


use App\Entity\GrrEntry;

class MonFactory implements FactoryInterface
{
    /**
     * @return object
     */
    public function createNew()
    {
        return new GrrEntry();
    }
}