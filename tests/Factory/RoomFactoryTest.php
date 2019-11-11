<?php

namespace App\Tests\Factory;

use App\Area\AreaFactory;
use App\Entity\Room;
use App\Room\RoomFactory;
use App\Tests\BaseTesting;

class RoomFactoryTest extends BaseTesting
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
        parent::setUp();
        $this->areaFactory = new AreaFactory();
        $this->roomFactory = new RoomFactory();
    }

    public function testCreateNew(): void
    {
        $area = $this->areaFactory->createNew();
        $area->setName('Lulu');

        $room = $this->roomFactory->createNew($area);

        $this->assertInstanceOf(Room::class, $room);
        $this->assertSame('Lulu', $room->getArea()->getName());
    }
}
