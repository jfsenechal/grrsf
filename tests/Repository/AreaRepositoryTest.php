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

    public function testLoadAFile()
    {
        $this->loader->load([
            __DIR__.'/../DataFixtures/area.yaml'
        ]);

        $result = $this->entityManager->getRepository(Area::class)->findAll();

        $this->assertEquals(1, count($result));
    }

}
