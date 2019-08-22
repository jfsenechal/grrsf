<?php


namespace App\Tests\Repository;

use App\Entity\EntryType;

class EntryTypeRepositoryTest extends BaseRepository
{
    public function testFindByName()
    {
        $entryType = $this->entityManager
            ->getRepository(EntryType::class)
            ->findOneBy(['name' => 'Cours']);

        $this->assertEquals('Cours', $entryType->getName());
    }


}
