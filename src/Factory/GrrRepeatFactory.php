<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:30
 */

namespace App\Factory;

use App\Entity\GrrRepeat;

class GrrRepeatFactory
{
    /** @var FactoryInterface */
    private $factory;

    public function construct(
        FactoryInterface $factory
    ) {
        $this->factory = $factory;
    }

    public function createNew(): GrrRepeat
    {
        return $this->factory->createNew();
    }
}