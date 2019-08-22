<?php

namespace App\DataFixtures\Install;

use App\Factory\TypeEntryFactory;
use App\Repository\EntryTypeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @var TypeEntryFactory
     */
    private $typeEntryFactory;
    /**
     * @var EntryTypeRepository
     */
    private $entryTypeRepository;

    public function __construct(EntryTypeRepository $entryTypeRepository, TypeEntryFactory $typeEntryFactory)
    {
        $this->typeEntryFactory = $typeEntryFactory;
        $this->entryTypeRepository = $entryTypeRepository;
    }

    public function load(ObjectManager $manager)
    {
        $types = [
            'A' => 'Cours',
            'B' => 'Reunion',
            'C' => 'Location',
            'D' => 'Bureau',
            'E' => 'Mise a disposition',
            'F' => 'Non disponible',
        ];

        foreach ($types as $index => $nom) {
            if ($this->entryTypeRepository->findOneBy(['name' => $nom])) {
                continue;
            }
            $type = $this->typeEntryFactory->createNew();
            $type->setLetter($index);
            $type->setName($nom);
            $manager->persist($type);
            $this->addReference('entry-type-'.$index, $type);
        }
        $manager->flush();
    }
}
