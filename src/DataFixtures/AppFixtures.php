<?php

namespace App\DataFixtures;

use App\Factory\TypeEntryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types = [
            'A' => 'Cours',
            'B' => 'Cours ext',
            'C' => 'Reunion',
            'D' => 'Location',
            'E' => 'Bureau',
            'F' => 'Mise a disposition',
            'G' => 'Non disponible',
        ];

        foreach ($types as $index => $nom) {
            $type = TypeEntryFactory::createNew();
            TypeEntryFactory::setDefaultValues($type);
            $type->setTypeLetter($index);
            $type->setTypeName($nom);
            $manager->persist($type);
            $this->addReference('entry-type-'.$index, $type);
        }
        $manager->flush();
    }
}
