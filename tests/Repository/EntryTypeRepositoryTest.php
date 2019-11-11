<?php

namespace App\Tests\Repository;

use App\Entity\EntryType;
use App\Tests\BaseTesting;

class EntryTypeRepositoryTest extends BaseTesting
{
    public function testFindByName(): void
    {
        $this->loadFixtures();
        $entryType = $this->entityManager
            ->getRepository(EntryType::class)
            ->findOneBy(['name' => 'Cours']);

        $this->assertEquals('Cours', $entryType->getName());
        $this->assertEquals('A', $entryType->getLetter());
    }

    protected function loadFixtures(): void
    {
        $files =
            [
                $this->pathFixtures.'entry_type.yaml',
            ];

        $this->loader->load($files);
    }
}
