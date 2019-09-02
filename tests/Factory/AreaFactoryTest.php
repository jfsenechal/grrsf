<?php

namespace App\Tests\Factory;

use App\Entity\Area;
use App\Factory\AreaFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AreaFactoryTest extends WebTestCase
{
    /**
     * @var AreaFactory
     */
    private $areaFactory;

    protected function setUp(): void
    {
        $this->areaFactory = new AreaFactory();
    }

    public function testCreateNew()
    {
        $area = $this->areaFactory->createNew();
        $area->setName('Lolo');

        $this->assertInstanceOf(Area::class, $area);
        $this->assertSame('Lolo', $area->getName());
    }

    public function testZe()
    {
        $this->markTestIncomplete('');
    }
}
