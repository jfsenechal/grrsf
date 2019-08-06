<?php

namespace App\DataFixtures;

use App\Factory\AreaFactory;
use App\Factory\EntryFactory;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EntryFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var AreaFactory
     */
    private $areaFactory;

    public function __construct(AreaFactory $areaFactory)
    {
        $this->areaFactory = $areaFactory;
    }

    public function load(ObjectManager $manager)
    {
        $entry = EntryFactory::createNew();
        EntryFactory::setDefaultValues($entry);
        $entry->setRoom($this->getReference('Box'));
        $entry->setStartTime(Carbon::today()->setTime(10, 0));
        $entry->setEndTime(Carbon::today()->setTime(14, 10));
        $entry->setName('Réunion');
        $entry->setType($this->getReference("entry-type-C"));
        $manager->persist($entry);

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [AppFixtures::class, AreaFixtures::class];
    }
}
