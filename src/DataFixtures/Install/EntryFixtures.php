<?php

namespace App\DataFixtures\Install;

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
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var EntryFactory
     */
    private $entryFactory;

    public function __construct(EntryFactory $entryFactory, AreaFactory $areaFactory)
    {
        $this->areaFactory = $areaFactory;
        $this->entryFactory = $entryFactory;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $start = Carbon::today()->setTime(10, 0);
        $end = Carbon::today()->setTime(14, 10);
        $this->createEntry('Réunion cst', $start, $end, 'Box', 'entry-type-C');

        $start = Carbon::today()->setTime(9, 0);
        $end = Carbon::today()->setTime(15, 35);
        $this->createEntry('Réunion pssp', $start, $end, 'Relax Room', 'entry-type-D');

        $start = Carbon::tomorrow()->setTime(16, 0);
        $end = Carbon::tomorrow()->setTime(17, 20);
        $this->createEntry('Réunion henalux', $start, $end, 'Relax Room', 'entry-type-A');

        $start = Carbon::yesterday()->setTime(8, 0);
        $end = Carbon::yesterday()->setTime(12, 40);
        $this->createEntry('Réunion timesquare', $start, $end, 'Digital Room', 'entry-type-F');

        $manager->flush();
    }

    protected function createEntry($name, $start, $end, $room, $type)
    {
        $entry = $this->entryFactory->createNew();
        $entry->setRoom($this->getReference($room));
        $entry->setStartTime($start);
        $entry->setEndTime($end);
        $entry->setName($name);
        $entry->setType($this->getReference($type));
        $this->manager->persist($entry);
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array
     */
    public function getDependencies()
    {
        return [AppFixtures::class, AreaFixtures::class];
    }
}
