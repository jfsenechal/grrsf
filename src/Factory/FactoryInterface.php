<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:32.
 */

namespace App\Factory;

interface FactoryInterface
{
    public function createNew(): object;
}
