<?php

namespace App\DataFixtures;

use App\Factory\AreaFactory;
use App\Factory\RoomFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AreaFixtures extends Fixture
{
    /**
     * @var AreaFactory
     */
    private $areaFactory;
    /**
     * @var RoomFactory
     */
    private $roomFactory;

    public function __construct(AreaFactory $areaFactory, RoomFactory $roomFactory)
    {
        $this->areaFactory = $areaFactory;
        $this->roomFactory = $roomFactory;
    }

    public function load(ObjectManager $manager)
    {
        $esquare = $this->areaFactory->createNew();

        $esquare->setName('E-square');

        $hdv = $this->areaFactory->createNew();
        $hdv->setName('Hdv');

        $manager->persist($esquare);
        $manager->persist($hdv);

        $salles = [
            'Box',
            'Créative',
            'Meeting Room',
            'Relax Room',
            'Digital Room',
        ];

        foreach ($salles as $salle) {
            $room = $this->roomFactory->createNew($esquare);
            $room->setName($salle);
            $manager->persist($room);
            $this->addReference($salle, $room);
        }

        $salles = [
            'Salle Conseil',
            'Salle Collège',
            'Salle cafétaria',
        ];

        foreach ($salles as $salle) {
            $room = $this->roomFactory->createNew($hdv);
            $room->setName($salle);
            $manager->persist($room);
        }

        $manager->flush();
    }
}
