<?php


namespace App\Tests\Repository;

use App\Entity\Area;

class AreaRepositoryTest extends BaseRepository
{
    public function testSearchByCategoryName()
    {
        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
            ]
        );

        $products = $this->entityManager
            ->getRepository(Area::class)
            ->findOneBy(['name' => 'Esquare']);

        $this->assertEquals('Esquare', $products->getName());
    }

    public function testCountArea()
    {
        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
            ]
        );

        $result = $this->entityManager->getRepository(Area::class)->findAll();

        $this->assertEquals(12, count($result));
    }

}
