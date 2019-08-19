<?php

namespace App\Tests\Factory;


use App\Entity\Area;
use App\Entity\Room;
use App\Factory\AreaFactory;
use App\Factory\RoomFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoomFactoryTest extends WebTestCase
{
    /**
     * @var AreaFactory
     */
    private $areaFactory;
    /**
     * @var RoomFactory
     */
    private $roomFactory;

    protected function setUp(): void
    {
        $this->areaFactory = new AreaFactory();
        $this->roomFactory = new RoomFactory();
    }

    public function testCreateNew()
    {
        $area = $this->areaFactory->createNew();
        $area->setName('Lulu');

        $room = $this->roomFactory::createNew($area);

        $this->assertInstanceOf(Room::class, $room);
        $this->assertSame('Lulu', $room->getArea()->getName());
    }
}
