<?php


namespace App\Tests\Repository;

use App\Entity\Area;

class AreaRepositoryTest extends BaseRepository
{
    public function testSearchByCategoryName()
    {
        $products = $this->entityManager
            ->getRepository(Area::class)
            ->findOneBy(['name' => 'E-square']);

        $this->assertEquals('E-square', $products->getName());
    }

}
