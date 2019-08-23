<?php


namespace App\Tests\Repository;

use App\Entity\Area;

class AreaRepositoryTest extends BaseRepository
{
    public function testSearchByName()
    {
        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
            ]
        );

        $area = $this->getArea('Esquare');

        $this->assertEquals('Esquare', $area->getName());
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
