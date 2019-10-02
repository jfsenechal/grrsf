<?php

namespace App\Tests\Factory;

use App\Entity\Area;
use App\Area\AreaFactory;
use App\Tests\BaseTesting;

class AreaFactoryTest extends BaseTesting
{
    /**
     * @var AreaFactory
     */
    private $areaFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->areaFactory = new AreaFactory();
    }

    public function testCreateNew()
    {
        $area = $this->areaFactory->createNew();
        $area->setName('Lolo');

        $this->assertInstanceOf(Area::class, $area);
        $this->assertSame('Lolo', $area->getName());
    }
}
