<?php

namespace App\DataFixtures;

use App\Factory\AreaFactory;
use App\Factory\RoomFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AreaFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $esquare = AreaFactory::createNew();
        AreaFactory::setDefaultValues($esquare);

        $esquare->setName('E-square');

        $hdv = AreaFactory::createNew();
        AreaFactory::setDefaultValues($hdv);
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
            $room = RoomFactory::createNew($esquare);
            RoomFactory::setDefaultValues($room);
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
            $room = RoomFactory::createNew($hdv);
            RoomFactory::setDefaultValues($room);
            $room->setName($salle);
            $manager->persist($room);
        }

        $manager->flush();
    }
}
