<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:30
 */

namespace App\Factory;

use App\Entity\Entry;

class EntryFactory
{
    /** @var FactoryInterface */
    private $factory;

    public function __construct(
        FactoryInterface $factory
    ) {
        $this->factory = $factory;
    }

    public function createNew(): Entry
    {
        return new Entry();

        return $this->factory->createNew();
    }

    public function setDefaultValues(Entry $grrEntry)
    {
        $grrEntry
            ->setTimestamp(new \DateTime())
            ->setModerate(false)
            ->setJours(false);
    }
}