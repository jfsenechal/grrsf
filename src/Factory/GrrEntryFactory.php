<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:30
 */

namespace App\Factory;

use App\Entity\GrrEntry;

class GrrEntryFactory
{
    /** @var FactoryInterface */
    private $factory;

    public function __construct(
        FactoryInterface $factory
    ) {
        $this->factory = $factory;
    }

    public function createNew(): GrrEntry
    {
        return $this->factory->createNew();
    }
}