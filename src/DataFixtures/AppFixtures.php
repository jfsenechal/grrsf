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
            "A" => "Cours",
            "G" => "Cours ext",
            "B" => "Reunion",
            "H" => "Location",
            "D" => "Bureau",
            "C" => "Mise a disposition",
            "E" => "Non disponible",
        ];

        foreach ($types as $index => $nom) {
            $type = TypeEntryFactory::createNew();
            TypeEntryFactory::setDefaultValues($type);
            $type->setTypeLetter($index);
            $type->setTypeName($nom);
            $manager->persist($type);
            $this->addReference("entry-type-".$index, $type);
        }
        $manager->flush();
    }
}
