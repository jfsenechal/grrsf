<?php

namespace App\DataFixtures;

use App\Factory\TypeEntryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @var TypeEntryFactory
     */
    private $typeEntryFactory;

    public function __construct(TypeEntryFactory $typeEntryFactory)
    {
        $this->typeEntryFactory = $typeEntryFactory;
    }

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
            $type = $this->typeEntryFactory->createNew();
            $type->setTypeLetter($index);
            $type->setTypeName($nom);
            $manager->persist($type);
            $this->addReference('entry-type-'.$index, $type);
        }
        $manager->flush();
    }
}
