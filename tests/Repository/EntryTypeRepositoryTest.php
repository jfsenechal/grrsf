<?php

namespace App\Tests\Repository;

use App\Entity\EntryType;

class EntryTypeRepositoryTest extends BaseRepository
{
    public function testFindByName()
    {
        $this->loadFixtures();
        $entryType = $this->entityManager
            ->getRepository(EntryType::class)
            ->findOneBy(['name' => 'Cours']);

        $this->assertEquals('Cours', $entryType->getName());
        $this->assertEquals('A', $entryType->getLetter());
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'entry_type.yaml',
            ];

        $this->loader->load($files);
    }
}
